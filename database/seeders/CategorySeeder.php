<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'ขนม',
            'เครื่องครัว / ซอสปรุงรส',
            'ของทำความสะอาด',
            'อุปกรณ์การเรียน',
            'อุปกรณ์อิเล็กทรอนิกส์',
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }
    }
}