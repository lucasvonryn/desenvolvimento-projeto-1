<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    protected $table = 'equipment';

    protected $fillable = [
        'company_id',
        'brand',
        'model',
        'summary',
        'technical_specs',
        'wifi_specs',
        'security_specs',
        'max_wifi_devices',
        'recommended_devices',
    ];

    protected function casts(): array
    {
        return [
            'max_wifi_devices' => 'integer',
            'recommended_devices' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
