<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventBudaya extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'event_budaya';

    protected $fillable = [
        'kategori_event_id',
        'judul',
        'slug',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'poster',
        'status',
        'buka_pendaftaran',
        'kuota_peserta',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'buka_pendaftaran' => 'boolean',
            'kuota_peserta' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug)) {
                $baseSlug = Str::slug($model->judul);
                $slug = $baseSlug;
                $counter = 1;

                while (static::where('slug', $slug)->where('id', '!=', $model->id ?? 0)->exists()) {
                    $slug = $baseSlug.'-'.$counter;
                    $counter++;
                }

                $model->slug = $slug;
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriEvent::class, 'kategori_event_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pendaftar(): HasMany
    {
        return $this->hasMany(PendaftaranEvent::class, 'event_budaya_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function getTotalPesertaTerdaftarAttribute(): int
    {
        return (int) $this->pendaftar()
            ->where('status', '!=', 'batal')
            ->sum('jumlah_peserta');
    }

    public function getSisaKuotaAttribute(): ?int
    {
        if (is_null($this->kuota_peserta)) {
            return null; // Kuota tidak terbatas
        }

        return max(0, $this->kuota_peserta - $this->total_peserta_terdaftar);
    }

    public function getIsKuotaPenuhAttribute(): bool
    {
        if (is_null($this->kuota_peserta)) {
            return false;
        }

        return $this->sisa_kuota <= 0;
    }

    public function getIsPendaftaranBisaDilakukanAttribute(): bool
    {
        return $this->status === 'aktif'
            && $this->buka_pendaftaran
            && $this->status_turunan !== 'Selesai'
            && ! $this->is_kuota_penuh;
    }

    /**
     * Get derived dynamic status based on Asia/Jakarta WIB timezone
     */
    public function getStatusTurunanAttribute(): string
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        $startDate = Carbon::parse($this->tanggal_mulai)->startOfDay();
        $endDate = Carbon::parse($this->tanggal_selesai)->endOfDay();

        if ($today->lt($startDate)) {
            return 'Akan datang';
        }

        if ($today->gt($endDate)) {
            return 'Selesai';
        }

        return 'Berlangsung';
    }

    public function getStatusTurunanBadgeClassAttribute(): string
    {
        return match ($this->status_turunan) {
            'Akan datang' => 'bg-white text-aspal border-2 border-aspal',
            'Berlangsung' => 'bg-aspal text-kuning border-2 border-aspal',
            'Selesai' => 'bg-beton text-abu border-2 border-beton',
            default => 'bg-beton text-aspal',
        };
    }

    public function getIsMultiHariAttribute(): bool
    {
        return ! Carbon::parse($this->tanggal_mulai)->isSameDay($this->tanggal_selesai);
    }

    public function getPosterUrlAttribute(): ?string
    {
        if (! $this->poster) {
            return null;
        }

        if (Str::startsWith($this->poster, ['http://', 'https://'])) {
            return $this->poster;
        }

        return Storage::url($this->poster);
    }
}
