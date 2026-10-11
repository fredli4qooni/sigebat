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
        Schema::table('event_budaya', function (Blueprint $table) {
            $table->string('tipe_event', 20)->default('gratis')->after('kuota_peserta');
            $table->unsignedInteger('harga_tiket')->default(0)->after('tipe_event');
        });

        Schema::table('pendaftaran_event', function (Blueprint $table) {
            $table->unsignedInteger('nominal_per_tiket')->default(0)->after('jumlah_peserta');
            $table->unsignedInteger('total_tagihan')->default(0)->after('nominal_per_tiket');
            $table->string('status_pembayaran', 30)->default('gratis')->after('total_tagihan');
            $table->string('metode_pembayaran', 30)->nullable()->after('status_pembayaran');
            $table->string('bukti_pembayaran')->nullable()->after('metode_pembayaran');
            $table->timestamp('waktu_pembayaran')->nullable()->after('bukti_pembayaran');
            $table->foreignId('diverifikasi_oleh')->nullable()->after('waktu_pembayaran')->constrained('users')->nullOnDelete();
            $table->timestamp('waktu_verifikasi')->nullable()->after('diverifikasi_oleh');
            $table->text('catatan_verifikasi')->nullable()->after('waktu_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_event', function (Blueprint $table) {
            $table->dropForeign(['diverifikasi_oleh']);
            $table->dropColumn([
                'nominal_per_tiket',
                'total_tagihan',
                'status_pembayaran',
                'metode_pembayaran',
                'bukti_pembayaran',
                'waktu_pembayaran',
                'diverifikasi_oleh',
                'waktu_verifikasi',
                'catatan_verifikasi',
            ]);
        });

        Schema::table('event_budaya', function (Blueprint $table) {
            $table->dropColumn(['tipe_event', 'harga_tiket']);
        });
    }
};
