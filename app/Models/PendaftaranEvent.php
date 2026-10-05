<?php

namespace App\Models;

use Database\Factories\PendaftaranEventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_peserta' => 'integer',
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
}
