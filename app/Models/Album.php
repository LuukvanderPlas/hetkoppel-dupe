<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('isActive', true);
    }

    public function scopeSlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    public function media()
    {
        return $this->belongsToMany(Media::class)->withPivot('id');
    }
}
