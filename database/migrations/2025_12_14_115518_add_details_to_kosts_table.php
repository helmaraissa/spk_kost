<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('kosts', function (Blueprint $table) {
            // Menambahkan kolom yang kurang
            $table->integer('distance')->nullable()->after('address'); // Jarak (meter)
            $table->string('room_size')->nullable()->after('distance'); // Luas (misal: 3x4)
            $table->text('facilities')->nullable()->after('description'); // Fasilitas (WiFi, AC, dll)
        });
    }

    public function down()
    {
        Schema::table('kosts', function (Blueprint $table) {
            $table->dropColumn(['distance', 'room_size', 'facilities']);
        });
    }
};
