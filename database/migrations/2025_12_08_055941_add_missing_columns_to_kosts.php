<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kosts', function (Blueprint $table) {
            // 1. Cek & Tambah kolom facility_details
            if (!Schema::hasColumn('kosts', 'facility_details')) {
                $table->text('facility_details')->nullable()->after('description');
            }

            // 2. Cek & Tambah kolom total_rooms
            if (!Schema::hasColumn('kosts', 'total_rooms')) {
                $table->integer('total_rooms')->default(1)->after('price_per_month');
            }
        });
    }

    public function down()
    {
        Schema::table('kosts', function (Blueprint $table) {
            if (Schema::hasColumn('kosts', 'facility_details')) {
                $table->dropColumn('facility_details');
            }
            if (Schema::hasColumn('kosts', 'total_rooms')) {
                $table->dropColumn('total_rooms');
            }
        });
    }
};