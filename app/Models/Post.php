<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Post extends Model
{
    use HasFactory;

    protected $with = ["user", "categories"];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilter($query, array $filters)
    {
        if (isset($filters["search"])) {
            $query->where(function ($query) use ($filters) {
                $query->where("title", "like", "%" . $filters["search"] . "%")
                    ->orWhere("content", "like", "%" . $filters["search"] . "%");
            });
        }

    }
}
