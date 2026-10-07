<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dusuns', function (Blueprint $table): void {
            $table->string('foto_kepala_dusun_path', 512)->nullable()->after('nama_kepala_dusun');
        });
    }

    public function down(): void
    {
        Schema::table('dusuns', function (Blueprint $table): void {
            $table->dropColumn('foto_kepala_dusun_path');
        });
    }
};
