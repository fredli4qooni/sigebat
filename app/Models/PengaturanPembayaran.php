<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PengaturanPembayaran extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_pembayaran';

    protected $fillable = [
        'nama_bank_utama',
        'nomor_rekening_utama',
        'atas_nama_utama',
        'nama_bank_sekunder',
        'nomor_rekening_sekunder',
        'atas_nama_sekunder',
        'qris_image',
        'nama_qris',
        'nmid_qris',
        'petunjuk_transfer',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Dapatkan instans pengaturan tunggal (singleton) dengan fallback default
     */
    public static function getSetting(): self
    {
        $setting = self::first();

        if (! $setting) {
            $setting = self::create([
                'nama_bank_utama' => 'BANK LAMPUNG',
                'nomor_rekening_utama' => '381.03.01.09882.1',
                'atas_nama_utama' => 'BUMDES KAMPUNG GEDUNG BATIN',
                'nama_bank_sekunder' => 'BANK BRI',
                'nomor_rekening_sekunder' => '0182-01-002938-53-9',
                'atas_nama_sekunder' => 'UNIT PARIWISATA GEDUNG BATIN',
                'nama_qris' => 'BUMDes Wisata Budaya Gedung Batin',
                'nmid_qris' => 'ID1020038847291',
                'is_active' => true,
            ]);
        }

        return $setting;
    }

    /**
     * URL gambar QRIS (dari storage public atau null)
     */
    public function getQrisUrlAttribute(): ?string
    {
        if ($this->qris_image && Storage::disk('public')->exists($this->qris_image)) {
            return asset('storage/'.$this->qris_image);
        }

        return null;
    }

    /**
     * Nomor rekening utama murni tanpa titik atau tanda hubung untuk clipboard
     */
    public function getNomorRekeningUtamaRawAttribute(): string
    {
        return preg_replace('/[^0-9]/', '', (string) $this->nomor_rekening_utama);
    }

    /**
     * Nomor rekening sekunder murni tanpa titik atau tanda hubung untuk clipboard
     */
    public function getNomorRekeningSekunderRawAttribute(): string
    {
        return preg_replace('/[^0-9]/', '', (string) $this->nomor_rekening_sekunder);
    }
}
