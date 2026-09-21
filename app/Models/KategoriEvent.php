<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KategoriEvent extends Model
{
    use HasFactory;

    protected $table = 'kategori_event';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $model): void {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }

    public function eventBudaya(): HasMany
    {
        return $this->hasMany(EventBudaya::class, 'kategori_event_id');
    }

    public function isUsed(): bool
    {
        return $this->eventBudaya()->exists();
    }
}
