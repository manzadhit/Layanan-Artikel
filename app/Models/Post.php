<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Post extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'slug', 'content'];

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

        if(isset($filters["category"])) {
            $query->whereHas("categories", function($query) use($filters) {
                $query->where("slug", $filters["category"]);
            });
        }

        if(isset($filters["author"])) {
            $query->whereHas("user", function ($query) use ($filters) {
                $query->where("users.name", "like", "%" . $filters["author"] . "%");
            });
        }

    }
}
