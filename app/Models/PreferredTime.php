<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferredTime extends Model
{
    protected $fillable = [ 'time_id', 'day_id', 'user_id' ];
    use HasFactory;
}
