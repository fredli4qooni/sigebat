<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    ];

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

    public function scopeUmum(Builder $query): Builder
    {
        return $query->whereNull('objek_wisata_id');
    }

    public function scopeKhusus(Builder $query): Builder
    {
        return $query->whereNotNull('objek_wisata_id');
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
}
