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

class ObjekWisata extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'objek_wisata';

    protected $fillable = [
        'kategori_wisata_id',
        'nama',
        'slug',
        'deskripsi',
        'alamat',
        'latitude',
        'longitude',
        'jam_operasional',
        'harga_tiket',
        'kontak',
        'foto_utama',
        'status',
        'created_by',
        'updated_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug)) {
                $baseSlug = Str::slug($model->nama);
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

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'aktif');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriWisata::class, 'kategori_wisata_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function fasilitas(): HasMany
    {
        return $this->hasMany(Fasilitas::class, 'objek_wisata_id');
    }

    public function getGoogleMapsUrlAttribute(): string
    {
        return "https://www.google.com/maps/dir/?api=1&destination={$this->latitude},{$this->longitude}";
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (! $this->foto_utama) {
            return null;
        }

        if (Str::startsWith($this->foto_utama, ['http://', 'https://'])) {
            return $this->foto_utama;
        }

        return Storage::url($this->foto_utama);
    }
}
