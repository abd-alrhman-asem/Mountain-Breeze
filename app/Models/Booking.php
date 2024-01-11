<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'check_in',
        'check_out',
        'guest_number',
        'description',
        'room_type_id',
        'created_at',
    ];

    public function type():BelongsTo
    {
        return $this->belongsTo(RoomType::class,'room_type_id','id');
    }
}
