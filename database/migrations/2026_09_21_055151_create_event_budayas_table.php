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
        Schema::create('event_budaya', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_event_id')->constrained('kategori_event')->restrictOnDelete();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->string('lokasi');
            $table->string('poster')->nullable();
            $table->string('status')->default('aktif'); // aktif, pending
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
        Schema::dropIfExists('event_budaya');
    }
};
