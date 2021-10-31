<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plant extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'watering_instructions',
        'photo',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [];
}
