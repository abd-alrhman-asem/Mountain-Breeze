<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpCenter extends Model
{
    use HasFactory,SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'subject',
        'message',
        'created_at'
    ];
}
