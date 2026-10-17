<?php

namespace App\Models;

use Database\Factories\PenyewaanFasilitasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenyewaanFasilitas extends Model
{
    /** @use HasFactory<PenyewaanFasilitasFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'penyewaan_fasilitas';

    protected $fillable = [
        'kode_sewa',
        'fasilitas_id',
        'nama_penyewa',
        'email',
        'nomor_telepon',
        'asal_instansi',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'jumlah_tamu',
        'harga_per_hari',
        'total_tagihan',
        'catatan',
        'status_sewa',
        'status_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'waktu_pembayaran',
        'diverifikasi_oleh',
        'waktu_verifikasi',
        'catatan_verifikasi',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'durasi_hari' => 'integer',
            'jumlah_tamu' => 'integer',
            'harga_per_hari' => 'integer',
            'total_tagihan' => 'integer',
            'waktu_pembayaran' => 'datetime',
            'waktu_verifikasi' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->kode_sewa)) {
                $prefix = 'SGB-SWA-'.date('ym').'-';
                do {
                    $code = $prefix.strtoupper(Str::random(5));
                } while (static::where('kode_sewa', $code)->exists());

                $model->kode_sewa = $code;
            }
        });
    }

    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class, 'fasilitas_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function scopeDiajukan(Builder $query): Builder
    {
        return $query->where('status_sewa', 'diajukan');
    }

    public function scopeDikonfirmasi(Builder $query): Builder
    {
        return $query->where('status_sewa', 'dikonfirmasi');
    }

    public function scopeDigunakan(Builder $query): Builder
    {
        return $query->where('status_sewa', 'digunakan');
    }

    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status_sewa', 'selesai');
    }

    public function scopeBatal(Builder $query): Builder
    {
        return $query->where('status_sewa', 'batal');
    }

    public function scopeLunas(Builder $query): Builder
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    public function scopeMenungguVerifikasi(Builder $query): Builder
    {
        return $query->where('status_pembayaran', 'menunggu_verifikasi');
    }

    public function scopeMenungguPembayaran(Builder $query): Builder
    {
        return $query->where('status_pembayaran', 'menunggu_pembayaran');
    }

    public function getIsLunasAttribute(): bool
    {
        return $this->status_pembayaran === 'lunas';
    }

    public function getTotalTagihanFormatAttribute(): string
    {
        return 'Rp '.number_format($this->total_tagihan, 0, ',', '.');
    }

    public function getHargaPerHariFormatAttribute(): string
    {
        return 'Rp '.number_format($this->harga_per_hari, 0, ',', '.');
    }

    public function getStatusSewaLabelAttribute(): string
    {
        return match ($this->status_sewa) {
            'diajukan' => 'Pengajuan Baru',
            'dikonfirmasi' => 'Terkonfirmasi',
            'digunakan' => 'Sedang Digunakan',
            'selesai' => 'Sewa Selesai',
            'batal' => 'Dibatalkan',
            default => ucfirst($this->status_sewa),
        };
    }

    public function getStatusSewaBadgeClassAttribute(): string
    {
        return match ($this->status_sewa) {
            'diajukan' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'dikonfirmasi' => 'bg-sky-50 text-sky-700 border border-sky-200',
            'digunakan' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
            'selesai' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'batal' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi' => 'Menunggu Verifikasi Pengelola',
            'lunas' => 'Lunas',
            'ditolak' => 'Pembayaran Ditolak',
            default => ucfirst(str_replace('_', ' ', $this->status_pembayaran)),
        };
    }

    public function getStatusPembayaranBadgeClassAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'menunggu_pembayaran' => 'bg-amber-50 text-amber-700 border border-amber-200',
            'menunggu_verifikasi' => 'bg-blue-50 text-blue-700 border border-blue-200',
            'lunas' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'ditolak' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        if (! $this->bukti_pembayaran) {
            return null;
        }

        if (Str::startsWith($this->bukti_pembayaran, ['http://', 'https://'])) {
            return $this->bukti_pembayaran;
        }

        return Storage::url($this->bukti_pembayaran);
    }
}
