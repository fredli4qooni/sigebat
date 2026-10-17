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
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->boolean('bisa_disewa')->default(false)->after('foto');
            $table->unsignedInteger('harga_sewa_per_hari')->default(0)->after('bisa_disewa');
            $table->unsignedSmallInteger('kapasitas')->nullable()->after('harga_sewa_per_hari');
            $table->text('fasilitas_include')->nullable()->after('kapasitas');
            $table->string('status_ketersediaan', 20)->default('tersedia')->after('fasilitas_include');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fasilitas', function (Blueprint $table) {
            $table->dropColumn([
                'bisa_disewa',
                'harga_sewa_per_hari',
                'kapasitas',
                'fasilitas_include',
                'status_ketersediaan',
            ]);
        });
    }
};
