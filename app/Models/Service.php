<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lang',
    ];

    /**
     * Get all of the comments for the User
     *
     */
    public function rooms()
    {
        return $this->belongsToMany(Room::class);
    }
}
