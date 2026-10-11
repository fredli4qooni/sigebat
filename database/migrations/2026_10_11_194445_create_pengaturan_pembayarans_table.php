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
        Schema::create('pengaturan_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank_utama')->default('BANK LAMPUNG');
            $table->string('nomor_rekening_utama')->default('381.03.01.09882.1');
            $table->string('atas_nama_utama')->default('BUMDES KAMPUNG GEDUNG BATIN');
            $table->string('nama_bank_sekunder')->nullable()->default('BANK BRI');
            $table->string('nomor_rekening_sekunder')->nullable()->default('0182-01-002938-53-9');
            $table->string('atas_nama_sekunder')->nullable()->default('UNIT PARIWISATA GEDUNG BATIN');
            $table->string('qris_image')->nullable();
            $table->string('nama_qris')->default('BUMDes Wisata Budaya Gedung Batin');
            $table->string('nmid_qris')->nullable()->default('ID1020038847291');
            $table->text('petunjuk_transfer')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturan_pembayaran');
    }
};
