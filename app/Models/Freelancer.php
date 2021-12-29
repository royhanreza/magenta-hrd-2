<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Freelancer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function members()
    {
        return $this->hasMany(EventMember::class);
    }

    // public function events() 
    // {
    //     return $this->hasManyThrough(Event::class, EventMember::class);
    // }
}
