<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::whereIn('role', ['employee', 'manager'])->latest()->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['required', Password::min(8)],
            'role' => ['nullable', 'in:employee,manager'],
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.unique' => 'อีเมลนี้ถูกใช้งานในระบบแล้ว',
            'phone.unique' => 'เบอร์โทรศัพท์นี้ถูกใช้งานในระบบแล้ว',
            'password.required' => 'กรุณากำหนดรหัสผ่านเริ่มต้น',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'employee',
            'status' => 'active',
        ]);

        return redirect()->route('admin.employees.index')->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required', 'in:employee,manager'],
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', "อัปเดตยศของ {$user->name} เป็น " . ($request->role === 'manager' ? 'หัวหน้าร้าน' : 'พนักงานทั่วไป') . " เรียบร้อยแล้ว");
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'ไม่สามารถลบบัญชีผู้ดูแลระบบสูงสุดได้');
        }

        $user->delete();

        return back()->with('success', 'ลบบัญชีเรียบร้อยแล้ว');
    }
}