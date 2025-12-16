<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomItem extends Model
{
    protected $fillable = [
        'room_id',
        'name',
        'quantity',
        'unit',
        'unit_price',
        'is_completed',
        'comment',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'is_completed' => 'boolean',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
