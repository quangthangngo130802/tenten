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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();  // Trường id tự động tăng
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');  // Khóa ngoại liên kết với bảng orders
            $table->string('service_name');  // Trường service_name
            $table->boolean('active');  // Trường active, kiểu boolean
            $table->string('domain');  // Trường domain
            $table->date('deadline');  // Trường deadline, kiểu date
            $table->decimal('amount', 10, 2);  // Trường amount, kiểu số thập phân với 2 chữ số sau dấu phẩy
            $table->timestamps();  // Các trường created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
