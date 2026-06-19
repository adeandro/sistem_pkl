<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Placement extends Model
{
    protected $fillable = ['company_name', 'quota', 'teacher_id'];

    protected $casts = [
        'quota' => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
