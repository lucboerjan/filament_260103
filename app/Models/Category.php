<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'description',
    ];

    public function parent(): HasOne
    {
        return $this->hasOne(Category::class);
    }
}
