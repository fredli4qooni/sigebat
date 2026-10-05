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
        Schema::create('pendaftaran_event', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_budaya_id')->constrained('event_budaya')->cascadeOnDelete();
            $table->string('kode_pendaftaran', 30)->unique();
            $table->string('nama_lengkap', 100);
            $table->string('email', 100);
            $table->string('nomor_telepon', 25);
            $table->string('asal_instansi', 100);
            $table->unsignedSmallInteger('jumlah_peserta')->default(1);
            $table->text('catatan')->nullable();
            $table->string('status', 20)->default('terdaftar'); // terdaftar, hadir, batal
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_event');
    }
};
