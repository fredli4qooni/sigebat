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
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_fasilitas_id')->constrained('jenis_fasilitas')->restrictOnDelete();
            $table->foreignId('objek_wisata_id')->nullable()->constrained('objek_wisata')->nullOnDelete();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('keterangan_lokasi')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas');
    }
};
