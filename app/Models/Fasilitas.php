<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Fasilitas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fasilitas';

    protected $fillable = [
        'jenis_fasilitas_id',
        'objek_wisata_id',
        'nama',
        'deskripsi',
        'keterangan_lokasi',
        'foto',
        'created_by',
        'bisa_disewa',
        'harga_sewa_per_hari',
        'kapasitas',
        'fasilitas_include',
        'status_ketersediaan',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'bisa_disewa' => 'boolean',
            'harga_sewa_per_hari' => 'integer',
            'kapasitas' => 'integer',
        ];
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(JenisFasilitas::class, 'jenis_fasilitas_id');
    }

    public function objekWisata(): BelongsTo
    {
        return $this->belongsTo(ObjekWisata::class, 'objek_wisata_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function penyewaan(): HasMany
    {
        return $this->hasMany(PenyewaanFasilitas::class, 'fasilitas_id');
    }

    public function scopeUmum(Builder $query): Builder
    {
        return $query->whereNull('objek_wisata_id');
    }

    public function scopeKhusus(Builder $query): Builder
    {
        return $query->whereNotNull('objek_wisata_id');
    }

    public function scopeBisaDisewa(Builder $query): Builder
    {
        return $query->where('bisa_disewa', true)->where('status_ketersediaan', 'tersedia');
    }

    public function getIsUmumAttribute(): bool
    {
        return is_null($this->objek_wisata_id);
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto) {
            return null;
        }

        if (Str::startsWith($this->foto, ['http://', 'https://'])) {
            return $this->foto;
        }

        return Storage::url($this->foto);
    }

    public function getHargaSewaFormatAttribute(): string
    {
        return 'Rp '.number_format($this->harga_sewa_per_hari, 0, ',', '.').' / hari';
    }

    public function getStatusKetersediaanLabelAttribute(): string
    {
        return match ($this->status_ketersediaan) {
            'tersedia' => 'Siap Digunakan',
            'perbaikan' => 'Dalam Perbaikan',
            'nonaktif' => 'Nonaktif',
            default => ucfirst($this->status_ketersediaan ?? 'tersedia'),
        };
    }

    public function getStatusKetersediaanBadgeClassAttribute(): string
    {
        return match ($this->status_ketersediaan) {
            'tersedia' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'perbaikan' => 'bg-amber-50 text-amber-700 border-amber-200',
            'nonaktif' => 'bg-slate-100 text-slate-700 border-slate-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }

    /**
     * Cek apakah fasilitas tersedia pada rentang tanggal tertentu (anti double-booking)
     */
    public function isTersediaRentang(string $start, string $end, ?int $excludeSewaId = null): bool
    {
        if (! $this->bisa_disewa || $this->status_ketersediaan !== 'tersedia') {
            return false;
        }

        $query = $this->penyewaan()
            ->whereNotIn('status_sewa', ['batal'])
            ->where('status_pembayaran', '!=', 'ditolak')
            ->where(function (Builder $q) use ($start, $end): void {
                $q->where('tanggal_mulai', '<=', $end)
                    ->where('tanggal_selesai', '>=', $start);
            });

        if ($excludeSewaId) {
            $query->where('id', '!=', $excludeSewaId);
        }

        return ! $query->exists();
    }
}
