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
        Schema::create('sub_criterias', function (Blueprint $table) {
            $table->id('sub_id');
            // Relasi ke Kriteria Induk
            $table->foreignId('criteria_id')->constrained('criterias', 'criteria_id')->onDelete('cascade');
            $table->string('name'); // Contoh: ">= 1.000.000" atau "Jarak < 500m"
            $table->float('value'); // Nilai bobot (1, 2, 3, 4, 5)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_criterias');
    }
};
