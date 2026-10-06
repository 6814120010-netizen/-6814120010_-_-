namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@store.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Employee
        User::create([
            'name' => 'John Employee',
            'email' => 'emp@store.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
        ]);

        // Customer
        User::create([
            'name' => 'Alice Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }
}