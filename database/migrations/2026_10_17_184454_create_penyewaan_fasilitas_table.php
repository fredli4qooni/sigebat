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
        Schema::create('penyewaan_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_sewa')->unique();
            $table->foreignId('fasilitas_id')->constrained('fasilitas')->cascadeOnDelete();
            $table->string('nama_penyewa');
            $table->string('email');
            $table->string('nomor_telepon');
            $table->string('asal_instansi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedInteger('durasi_hari');
            $table->unsignedInteger('jumlah_tamu')->default(1);
            $table->unsignedInteger('harga_per_hari');
            $table->unsignedInteger('total_tagihan');
            $table->text('catatan')->nullable();
            $table->string('status_sewa', 30)->default('diajukan');
            $table->string('status_pembayaran', 30)->default('menunggu_pembayaran');
            $table->string('metode_pembayaran', 50)->nullable();
            $table->string('bukti_pembayaran')->nullable();
            $table->dateTime('waktu_pembayaran')->nullable();
            $table->foreignId('diverifikasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('waktu_verifikasi')->nullable();
            $table->text('catatan_verifikasi')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan_fasilitas');
    }
};
