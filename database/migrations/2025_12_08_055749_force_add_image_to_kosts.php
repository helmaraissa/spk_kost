<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up()
        {
            Schema::table('kosts', function (Blueprint $table) {
                // Cek dulu biar gak error kalau kolomnya ternyata udah ada
                if (!Schema::hasColumn('kosts', 'image')) {
                    $table->string('image')->nullable()->after('name');
                }
            });
        }

        public function down()
        {
            Schema::table('kosts', function (Blueprint $table) {
                if (Schema::hasColumn('kosts', 'image')) {
                    $table->dropColumn('image');
                }
            });
        }
    };