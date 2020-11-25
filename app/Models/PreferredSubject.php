<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferredSubject extends Model
{
    protected $fillable = [ 'subject_id', 'user_id' ];
    use HasFactory;
}
