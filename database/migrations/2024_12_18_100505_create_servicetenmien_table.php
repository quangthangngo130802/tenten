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
        Schema::create('servicrtenmien', function (Blueprint $table) {
            $table->id();  // Khóa chính tự động
            $table->integer('type');  // Phí duy trì (dạng số thập phân)
            $table->string('tenmien');  // Tên miền
            $table->date('ngay_bat_dau');  // Ngày bắt đầu
            $table->date('ngay_ket_thuc');  // Ngày kết thúc
            $table->string('DNS');  // DNS
            $table->boolean('active')->default(true);  // Trạng thái hoạt động (mặc định là true)
            $table->decimal('phi_dang_ky', 10, 2)->nullable();  // Phí đăng ký (dạng số thập phân)
            $table->decimal('phi_duy_tri', 10, 2)->nullable();  // Phí duy trì (dạng số thập phân)
            $table->timestamps();  // Thời gian tạo và cập nhật bản ghi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicetenmien');
    }
};
