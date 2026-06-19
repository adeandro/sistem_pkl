<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Student extends Model
{
    protected $fillable = ['name', 'nis', 'is_assigned'];

    protected $casts = [
        'is_assigned' => 'boolean',
    ];

    public function placements(): BelongsToMany
    {
        return $this->belongsToMany(Placement::class);
    }
}
