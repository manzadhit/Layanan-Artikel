<?php

// app/Models/Image.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['post_id', 'image_path'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

