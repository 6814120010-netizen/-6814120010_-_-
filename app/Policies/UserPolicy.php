<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // 1. พนักงานห้ามดูข้อมูล Admin
    public function view(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isEmployee() && $targetUser->isAdmin()) {
            return false;
        }

        return $currentUser->isAdmin() || $currentUser->id === $targetUser->id;
    }

    // 2. Admin แก้โปรไฟล์คนอื่นได้ / ลูกค้าแก้ได้แค่ตัวเอง
    public function update(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isCustomer()) {
            return $currentUser->id === $targetUser->id;
        }

        return $currentUser->isAdmin();
    }

    // 3. ห้ามทุกคนเปลี่ยนรหัสผ่านของผู้อื่น (รวมถึง Admin)
    public function updatePassword(User $currentUser, User $targetUser): bool
    {
        return $currentUser->id === $targetUser->id;
    }

    // 4. Admin และ พนักงาน สมัครบัญชีให้ลูกค้าได้
    public function createCustomer(User $currentUser): bool
    {
        return $currentUser->isAdmin() || $currentUser->isEmployee();
    }
}