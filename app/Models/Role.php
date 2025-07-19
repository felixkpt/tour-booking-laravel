<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasFactory, CommonModelRelationShips;

    protected $fillable = ['name', 'description', 'guard_name', 'status_id'];

}
