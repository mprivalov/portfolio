<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'excerpt',
        'body',
        'image',
        'reposts',
        'likes',
    ];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class);
    }

    public function likesCount(): HasMany
    {

        return $this->hasMany(Like::class);
    }

    public function comments(): HasMany
    {

        return $this->hasMany(Comment::class);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {

            return asset('storage/' . $this->image);
        }

        return '';
    }

    public function reposts(): HasMany
    {

        return $this->hasMany(Repost::class);
    }
}
