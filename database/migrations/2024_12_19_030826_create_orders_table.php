<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();  // Tạo trường id tự động tăng
            $table->string('code');  // Trường 'code'
            $table->string('email');  // Trường 'email'
            $table->string('fullname');  // Trường 'fullname'
            $table->decimal('amount', 10, 2);  // Trường 'amount' (số thập phân với 2 chữ số sau dấu phẩy)
            $table->string('status');  // Trường 'status'
            $table->timestamps();  // Các trường created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
