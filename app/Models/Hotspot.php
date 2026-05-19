<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hotspot extends Model
{
    protected $fillable = [
        'location_id',
        'target_location_id',
        'yaw',
        'pitch',
        'label',
        'description',
        'type',
        'icon',
        'url',
    ];

    protected $casts = [
        'yaw' => 'float',
        'pitch' => 'float',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function targetLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'target_location_id');
    }
}
