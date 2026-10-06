<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique(); // เลขที่ใบเสร็จ
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // พนักงานขาย
            $table->decimal('total_amount', 10, 2); // ยอดรวมทั้งสิ้น
            $table->decimal('paid_amount', 10, 2);  // จำนวนเงินที่รับมา
            $table->decimal('change_amount', 10, 2); // เงินทอน
            $table->string('payment_method')->default('cash'); // วิธีชำระเงิน (cash, qr)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};