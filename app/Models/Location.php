<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = [
        'building_id',
        'name',
        'description',
        'image_path',
        'hfov',
        'yaw',
        'pitch',
        'map_x',
        'map_y',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hfov' => 'float',
        'yaw' => 'float',
        'pitch' => 'float',
        'map_x' => 'float',
        'map_y' => 'float',
        'sort_order' => 'integer',
    ];

    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function hotspots(): HasMany
    {
        return $this->hasMany(Hotspot::class);
    }
}
