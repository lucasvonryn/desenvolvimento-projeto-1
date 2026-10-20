<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    protected $fillable = [
        'name',
        'responsible_name',
        'responsible_phone',
        'address',
        'layout_description',
        'notebooks_count',
        'smartphones_count',
        'cameras_count',
        'other_devices_count',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'notebooks_count' => 'integer',
            'smartphones_count' => 'integer',
            'cameras_count' => 'integer',
            'other_devices_count' => 'integer',
        ];
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class)->orderBy('sort_order')->orderBy('name');
    }

    public function equipment(): HasOne
    {
        return $this->hasOne(Equipment::class);
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(Measurement::class);
    }

    public function totalDevices(): int
    {
        return $this->notebooks_count
            + $this->smartphones_count
            + $this->cameras_count
            + $this->other_devices_count;
    }
}
