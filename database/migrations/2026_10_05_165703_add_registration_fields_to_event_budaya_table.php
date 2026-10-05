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
            $table->boolean('buka_pendaftaran')->default(true)->after('status');
            $table->unsignedInteger('kuota_peserta')->nullable()->after('buka_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_budaya', function (Blueprint $table) {
            $table->dropColumn(['buka_pendaftaran', 'kuota_peserta']);
        });
    }
};
