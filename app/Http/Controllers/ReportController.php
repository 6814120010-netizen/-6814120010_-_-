<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // แสดงหน้าประวัติการขาย และสรุปยอดขาย
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])->latest();

        // กรองตามช่วงวันที่
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // ค้นหาตามเลขที่ใบเสร็จ
        if ($request->filled('search')) {
            $query->where('order_no', 'like', "%{$request->search}%");
        }

        // คำนวณยอดสรุป
        $totalSales = (clone $query)->sum('total_amount');
        $totalOrders = (clone $query)->count();

        // ดึงข้อมูลรายการขาย (แบ่งหน้าละ 10 รายการ)
        $orders = $query->paginate(10)->withQueryString();

        return view('reports.index', compact('orders', 'totalSales', 'totalOrders'));
    }

    // ดึงข้อมูลรายละเอียดใบเสร็จ (JSON สำหรับ Modal)
    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);
        return response()->json($order);
    }
}