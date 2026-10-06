<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // 1. อัปเดตข้อมูลส่วนตัว (ชื่อ, เบอร์โทร)
    public function updateProfile(Request $request, User $user)
    {
        // ตรวจสอบสิทธิ์: ป้องกันการแก้บัญชีคนอื่น (ต้องเป็นเจ้าของบัญชีเท่านั้น)
        if (Auth::id() !== $user->id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อมูลบัญชีนี้');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20', 'unique:users,phone,' . $user->id],
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'อัปเดตข้อมูลส่วนตัวเรียบร้อยแล้ว');
    }

    // 2. เปลี่ยนรหัสผ่าน
    public function updatePassword(Request $request, User $user)
    {
        // ตรวจสอบสิทธิ์: ต้องเป็นเจ้าของบัญชีเท่านั้น
        if (Auth::id() !== $user->id) {
            abort(403, 'คุณไม่มีสิทธิ์แก้ไขข้อมูลบัญชีนี้');
        }

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'current_password.current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.confirmed' => 'การยืนยันรหัสผ่านใหม่ไม่ตรงกัน',
            'password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
    }

    // 3. ยกเลิกบัญชีผู้ใช้ (Soft Delete)
    public function destroySelf(Request $request)
    {
        $user = Auth::user();

        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'บัญชีของคุณถูกยกเลิกเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        // เช็กว่าถ้าไม่ใช่ Admin จะไม่ยอมให้ลบข้อมูล
        if (auth()->user()->role !== 'admin') {
            abort(403, 'เฉพาะ Admin เท่านั้นที่สามารถลบบัญชีได้');
        }

        $employee = User::findOrFail($id);
        $employee->delete();

        return redirect()->back()->with('success', 'ลบบัญชีผู้ใช้เรียบร้อยแล้ว');
    }
}