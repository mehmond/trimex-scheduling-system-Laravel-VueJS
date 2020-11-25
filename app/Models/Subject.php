<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable =[
        'name',
        'description',
        'lec',
        'lab',
        'type',
        'course_id',
        'year_id'
    ];
    use HasFactory;

    public function course(){
        return $this->belongsTo('App\Models\Course');
    }
}
