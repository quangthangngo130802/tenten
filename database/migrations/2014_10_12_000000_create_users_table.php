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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();       // Tên đăng nhập
            $table->string('password');                // Mật khẩu
            $table->string('full_name');               // Họ và tên
            $table->string('gender')->nullable();      // Giới tính
            $table->date('birth_date')->nullable();    // Ngày sinh
            $table->string('identity_number')->nullable(); // CMND/CCCD/Hộ chiếu
            $table->string('tax_code')->nullable(); // Mã số thuế
            $table->string('country')->nullable();     // Quốc gia
            $table->string('province')->nullable();    // Tỉnh thành
            $table->string('district')->nullable();    // Quận/Huyện
            $table->string('ward')->nullable();        // Xã/Phường
            $table->string('address')->nullable();     // Địa chỉ
            $table->string('phone_number')->nullable();// Điện thoại
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('role_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
