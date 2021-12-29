<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function permissionCategory()
    {
        return $this->belongsTo(PermissionCategory::class);
    }
}
