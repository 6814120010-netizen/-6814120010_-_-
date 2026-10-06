<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // แสดงฟอร์มสมัครสมาชิกให้ลูกค้า
    public function create()
    {
        return view('admin.employees.customers.create');
    }

    // บันทึกข้อมูลสมาชิกใหม่
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุลลูกค้า',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์ลูกค้า',
            'phone.unique' => 'เบอร์โทรศัพท์นี้เป็นสมาชิกในระบบอยู่แล้ว',
            'email.unique' => 'อีเมลนี้ถูกใช้งานในระบบแล้ว',
        ]);

        // กรณีลูกค้าไม่ได้ให้อีเมล ระบบจะสร้างให้อัตโนมัติจากเบอร์โทร
        $email = $request->email ?: $request->phone . '@customer.store';

        User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $email,
            'password' => Hash::make($request->phone), // กำหนดรหัสผ่านเริ่มต้นเป็นเบอร์โทรศัพท์
            'role' => 'customer',
            'status' => 'active',
            'points' => 0,
        ]);

        // เปลี่ยนบรรทัด return อัปเดตเป็น:
return redirect()->route('pos.index')->with('success', 'สมัครสมาชิกให้คุณ ' . $request->name . ' เรียบร้อยแล้ว (รหัสผ่านเริ่มต้นคือเบอร์โทรศัพท์)');
    }
}