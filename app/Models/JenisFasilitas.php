<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisFasilitas extends Model
{
    use HasFactory;

    protected $table = 'jenis_fasilitas';

    protected $fillable = [
        'nama',
        'icon',
    ];

    public function fasilitas(): HasMany
    {
        return $this->hasMany(Fasilitas::class, 'jenis_fasilitas_id');
    }

    public function isUsed(): bool
    {
        return $this->fasilitas()->exists();
    }
}
