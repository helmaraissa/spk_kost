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
        Schema::create('criterias', function (Blueprint $table) {
            $table->id('criteria_id');
            $table->string('code'); // Contoh: C1, C2
            $table->string('criteria_name'); // Contoh: Harga, Fasilitas
            $table->enum('attribute', ['benefit', 'cost']);
            $table->float('weight'); // Contoh: 0.35
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};
