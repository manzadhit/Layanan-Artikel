<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    protected $fillable = ['user_id', 'post_id'];

    // Relasi untuk pengguna yang menyimpan
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi untuk postingan yang disimpan
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
