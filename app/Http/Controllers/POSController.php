<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller
{
    // แสดงหน้าขายสินค้า
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::where('stock', '>', 0);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $products = $query->get();

        return view('pos.index', compact('products', 'categories'));
    }


    // ค้นหาสมาชิกด้วยเบอร์โทรศัพท์สำหรับหน้า POS
    public function findMember(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $member = User::where('phone', $request->phone)
            ->where('role', 'customer')
            ->where('status', 'active')
            ->first();

        if (!$member) {
            return response()->json([
                'found' => false,
                'message' => 'ไม่พบสมาชิกจากเบอร์โทรศัพท์นี้',
            ], 404);
        }

        return response()->json([
            'found' => true,
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'phone' => $member->phone,
                'points' => $member->points,
            ],
        ]);
    }

    // บันทึกรายการขาย / ตัดสต็อก
    public function checkout(Request $request)
    {
        $request->validate([
            'cart' => 'required|json',
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'member_phone' => 'nullable|string|max:20',
        ]);

        $cart = json_decode($request->cart, true);

        $member = null;
        if ($request->filled('member_phone')) {
            $member = User::where('phone', $request->member_phone)
                ->where('role', 'customer')
                ->where('status', 'active')
                ->first();

            if (!$member) {
                return back()->with('error', 'ไม่พบสมาชิกจากเบอร์โทรศัพท์ที่ระบุ');
            }
        }

        if (empty($cart)) {
            return back()->with('error', 'ไม่มีสินค้าในตะกร้า');
        }

        DB::beginTransaction();
        try {
            // คำนวณยอดรวม
            $totalAmount = 0;
            foreach ($cart as $item) {
                $totalAmount += $item['price'] * $item['qty'];
            }

            if ($request->paid_amount < $totalAmount) {
                return back()->with('error', 'จำนวนเงินที่รับมาไม่พอจ่าย');
            }

            // สร้าง Order
            $order = Order::create([
                'order_no' => 'POS-' . date('YmdHis') . '-' . rand(100, 999),
                'user_id' => Auth::id() ?? 1,
                'customer_id' => $member?->id,
                'total_amount' => $totalAmount,
                'paid_amount' => $request->paid_amount,
                'change_amount' => $request->paid_amount - $totalAmount,
                'payment_method' => $request->payment_method,
            ]);

            // บันทึกรายการสินค้า + ตัดสต็อก
            foreach ($cart as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->stock < $item['qty']) {
                    DB::rollBack();
                    return back()->with('error', "สินค้า {$product->name} มีสต็อกไม่พอ");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // ตัดสต็อก
                $product->decrement('stock', $item['qty']);
            }

            // คำนวณสะสมแต้ม (100 บาท = 1 แต้ม)
            $earnedPoints = floor($totalAmount / 100);
            if ($earnedPoints > 0 && Auth::check()) {
                ($member ?? Auth::user())->increment('points', $earnedPoints);
                }

            DB::commit();

            return redirect()->route('pos.index')->with('success', "ชำระเงินสำเร็จ! เลขที่ใบเสร็จ: {$order->order_no} (เงินทอน ฿" . number_format($order->change_amount, 2) . ")");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }
}