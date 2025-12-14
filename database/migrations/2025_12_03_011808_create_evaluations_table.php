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
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id('evaluation_id');
            // Relasi ke Kost (Alternatif)
            $table->foreignId('kost_id')->constrained('kosts', 'kost_id')->onDelete('cascade');
            // Relasi ke Kriteria
            $table->foreignId('criteria_id')->constrained('criterias', 'criteria_id')->onDelete('cascade');
            $table->float('value'); // Nilai bobot (misal: 1, 2, 3, 4, 5) diambil dari SubCriteria
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
