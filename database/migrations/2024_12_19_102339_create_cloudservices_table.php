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
        Schema::create('cloudservices', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id')->nullable();
            $table->string('package_name')->nullable(); // Tên gói
            $table->string('cpu')->nullable();          // CPU
            $table->string('ram')->nullable();          // RAM
            $table->string('ssd')->nullable();          // SSD
            $table->string('network')->nullable();      // Mạng
            $table->decimal('price', 10, 2)->nullable(); // Giá (vnđ/tháng)
            $table->decimal('total_cost', 10, 2)->nullable(); // Tổng tiền (vnđ/năm)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cloudservices');
    }
};
