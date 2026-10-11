<?php

namespace App\Models;

use Database\Factories\PendaftaranEventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PendaftaranEvent extends Model
{
    /** @use HasFactory<PendaftaranEventFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'pendaftaran_event';

    protected $fillable = [
        'event_budaya_id',
        'kode_pendaftaran',
        'nama_lengkap',
        'email',
        'nomor_telepon',
        'asal_instansi',
        'jumlah_peserta',
        'catatan',
        'status',
        'nominal_per_tiket',
        'total_tagihan',
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
            'jumlah_peserta' => 'integer',
            'nominal_per_tiket' => 'integer',
            'total_tagihan' => 'integer',
            'waktu_pembayaran' => 'datetime',
            'waktu_verifikasi' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->kode_pendaftaran)) {
                $prefix = 'SGB-EVT-'.date('ym').'-';
                do {
                    $code = $prefix.strtoupper(Str::random(5));
                } while (static::where('kode_pendaftaran', $code)->exists());

                $model->kode_pendaftaran = $code;
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(EventBudaya::class, 'event_budaya_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function scopeTerdaftar(Builder $query): Builder
    {
        return $query->where('status', 'terdaftar');
    }

    public function scopeHadir(Builder $query): Builder
    {
        return $query->where('status', 'hadir');
    }

    public function scopeBatal(Builder $query): Builder
    {
        return $query->where('status', 'batal');
    }

    public function scopeLunas(Builder $query): Builder
    {
        return $query->whereIn('status_pembayaran', ['lunas', 'gratis']);
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
        return in_array($this->status_pembayaran, ['lunas', 'gratis'], true);
    }

    public function getTotalTagihanFormatAttribute(): string
    {
        return $this->total_tagihan > 0
            ? 'Rp '.number_format($this->total_tagihan, 0, ',', '.')
            : 'Gratis';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'terdaftar' => 'Terdaftar',
            'hadir' => 'Hadir di Lokasi',
            'batal' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'terdaftar' => 'bg-sky-50 text-sky-700 border border-sky-200',
            'hadir' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'batal' => 'bg-rose-50 text-rose-700 border border-rose-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };
    }

    public function getStatusPembayaranLabelAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'gratis' => 'Gratis (Bebas Biaya)',
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
            'gratis' => 'bg-slate-100 text-slate-700 border border-slate-200',
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
