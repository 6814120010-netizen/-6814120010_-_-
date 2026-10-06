<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // เชื่อมกับหมวดหมู่
            $table->string('code')->unique(); // รหัสสินค้า / บาร์โค้ด
            $table->string('name'); // ชื่อสินค้า
            $table->decimal('price', 10, 2); // ราคาขาย
            $table->integer('stock')->default(0); // จำนวนสต็อก
            $table->string('image')->nullable(); // รูปภาพสินค้า
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};