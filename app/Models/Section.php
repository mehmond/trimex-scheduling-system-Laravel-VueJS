<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['name'];
    use HasFactory;

    public function subject(){
        return $this->belongsToMany('App\Models\Subject', 'section_subjects');
    }
    public function course(){
        return $this->belongsToMany('App\Models\Course', 'section_subjects');
    }
    public function year(){
        return $this->belongsToMany('App\Models\Year', 'section_subjects');
    }
} 
