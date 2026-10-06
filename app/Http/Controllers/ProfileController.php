<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ดึงประวัติการสั่งซื้อเฉพาะของสมาชิกที่เข้าสู่ระบบอยู่
        $orders = Order::where(function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                        if (\Schema::hasColumn('orders', 'customer_id')) {
                            $query->orWhere('customer_id', $user->id);
                        }
                    })
                    ->with('items.product')
                    ->latest()
                    ->paginate(10);

        return view('profile.index', compact('user', 'orders'));
    }

public function destroy(Request $request)
{
    $user = Auth::user();

    // 1. ล็อกเอาต์ผู้ใช้ออกจากระบบ
    Auth::logout();

    // 2. ลบข้อมูลผู้ใช้ออกจากฐานข้อมูล
    $user->delete();

    // 3. ล้าง Session เพื่อความปลอดภัย
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/')->with('success', 'ลบบัญชีใช้งานของคุณเรียบร้อยแล้ว');
}
}