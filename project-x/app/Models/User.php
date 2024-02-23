<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likes(): HasMany
    {

        return $this->hasMany(Like::class);
    }

    public function alreadyFollowing()
    {
        $currentUser = Auth::user();

        return $this->followers()->where('follower_id', $currentUser->id)->first();
    }

    public function followers(): HasMany
    {

        return $this->hasMany(Followers::class, 'following_id');
    }

    public function following(): HasMany
    {

        return $this->hasMany(Followers::class, 'follower_id');
    }

    public function getAvatarPathAttribute()
    {

        return $this->avatar ? asset('storage/' . $this->avatar) : null;
    }

    public function reposts(): HasMany
    {

        return $this->hasMany(Repost::class);
    }

    public function hasReposted(Post $post)
    {
        
        return $this->reposts()->where('post_id', $post->id)->exists();
    }
}
