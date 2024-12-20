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
        Schema::create('hosting', function (Blueprint $table) {
            $table->id();
            $table->string('package_name')->nullable();  // Tên gói dịch vụ
            $table->string('storage')->nullable();        // Dung lượng
            $table->string('bandwidth')->nullable();      // Băng thông
            $table->integer('website_limit')->nullable(); // Giới hạn website
            $table->boolean('ssl_included')->nullable();  // Tích hợp SSL
            $table->decimal('price', 10, 2)->nullable();  // Đơn giá
            $table->string('tech_support')->nullable();    // Hỗ trợ kỹ thuật
            $table->string('backup_frequency')->nullable(); // Tần suất backup
            $table->timestamps();                           // Thời gian tạo và cập nhật
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting');
    }
};
