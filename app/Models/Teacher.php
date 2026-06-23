<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = ['name', 'nip', 'id_type'];

    public function placements()
    {
        return $this->hasMany(Placement::class);
    }
}
