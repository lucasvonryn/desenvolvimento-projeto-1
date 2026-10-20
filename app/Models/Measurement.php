<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Measurement extends Model
{
    public const TIME_SLOTS = ['08h', '13h', '17h'];

    protected $fillable = [
        'company_id',
        'room_id',
        'measured_on',
        'time_slot',
        'speed_24_mbps',
        'speed_5_mbps',
    ];

    protected function casts(): array
    {
        return [
            'measured_on' => 'date',
            'speed_24_mbps' => 'decimal:2',
            'speed_5_mbps' => 'decimal:2',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
