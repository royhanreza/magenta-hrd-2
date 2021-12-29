<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventMember extends Model
{
    use HasFactory, SoftDeletes;

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function freelancer()
    {
        return $this->belongsTo(Freelancer::class);
    }

}
