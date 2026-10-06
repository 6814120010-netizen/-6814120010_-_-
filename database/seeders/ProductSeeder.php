<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $snackCat = Category::where('name', 'ขนม')->first();
        $kitchenCat = Category::where('name', 'like', '%เครื่องครัว%')->first();
        $cleanCat = Category::where('name', 'ของทำความสะอาด')->first();
        $schoolCat = Category::where('name', 'อุปกรณ์การเรียน')->first();
        $gadgetCat = Category::where('name', 'อุปกรณ์อิเล็กทรอนิกส์')->first();

        $products = [
            // ขนม
            ['category_id' => $snackCat?->id ?? 1, 'code' => '8850001001', 'name' => 'มันฝรั่งทอดกรอบ รสมันแท้ 50ก.', 'price' => 20.00, 'stock' => 50],
            ['category_id' => $snackCat?->id ?? 1, 'code' => '8850001002', 'name' => 'เวเฟอร์สอดไส้ช็อกโกแลต 100ก.', 'price' => 15.00, 'stock' => 30],
            
            // เครื่องครัว / ซอส
            ['category_id' => $kitchenCat?->id ?? 2, 'code' => '8850002001', 'name' => 'ซอสหอยนางรม สูตรเข้มข้น 350ก.', 'price' => 35.00, 'stock' => 20],
            ['category_id' => $kitchenCat?->id ?? 2, 'code' => '8850002002', 'name' => 'น้ำปลาแท้ 100% 500มล.', 'price' => 32.00, 'stock' => 25],
            
            // ของทำความสะอาด
            ['category_id' => $cleanCat?->id ?? 3, 'code' => '8850003001', 'name' => 'น้ำยาซักผ้าสูตรเข้มข้น 700มล.', 'price' => 79.00, 'stock' => 15],
            ['category_id' => $cleanCat?->id ?? 3, 'code' => '8850003002', 'name' => 'น้ำยาล้างจาน กลิ่นมะนาว 450มล.', 'price' => 25.00, 'stock' => 40],
            
            // อุปกรณ์การเรียน
            ['category_id' => $schoolCat?->id ?? 4, 'code' => '8850004001', 'name' => 'ปากกาลูกลื่น น้ำเงิน 0.5มม.', 'price' => 10.00, 'stock' => 100],
            ['category_id' => $schoolCat?->id ?? 4, 'code' => '8850004002', 'name' => 'สมุดมีเส้น 80 แผ่น', 'price' => 20.00, 'stock' => 45],
            
            // อุปกรณ์อิเล็กทรอนิกส์
            ['category_id' => $gadgetCat?->id ?? 5, 'code' => '8850005001', 'name' => 'สายชาร์จ Fast Charge Type-C 1m', 'price' => 120.00, 'stock' => 10],
            ['category_id' => $gadgetCat?->id ?? 5, 'code' => '8850005002', 'name' => 'หัวชาร์จเร็ว USB-C 20W', 'price' => 250.00, 'stock' => 8],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['code' => $p['code']], $p);
        }
    }
}