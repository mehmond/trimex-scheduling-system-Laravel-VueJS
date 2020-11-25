<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable =[
        'name', 'campus_id', 'type'
    ];
    use HasFactory;
}
