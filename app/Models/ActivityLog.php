<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'aksi',
        'entitas_tipe',
        'entitas_id',
        'keterangan',
        'ip_address',
        'created_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'keterangan' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Quick helper to record an activity log
     *
     * @param  array<string, mixed>|null  $keterangan
     */
    public static function log(
        string $aksi,
        ?string $entitasTipe = null,
        ?int $entitasId = null,
        ?array $keterangan = null,
        ?int $userId = null,
        ?string $ip = null
    ): self {
        return static::create([
            'user_id' => $userId ?? auth()->id(),
            'aksi' => $aksi,
            'entitas_tipe' => $entitasTipe,
            'entitas_id' => $entitasId,
            'keterangan' => $keterangan,
            'ip_address' => $ip ?? request()?->ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
