<?php

namespace Coder\Models;

use Coder\Models\Status;
use Coder\Models\Post;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'location',
        'active',
        'active_hash',
        'active_at',
        'recover_hash',
        'city',
        'country',
        'country_code',
        'lat',
        'lon',
        'region',
        'region_name',
        'timezone',
        'facebook_id',
        'google_id',
        'linkedin_id',
        'ban',
        'ban_reason',
        'ban_at',
        'facebook_url',
        'google_url',
        'linkedin_url',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function getSocialId($driver)
    {
        $social = $driver . '_id';
        return $this->$social ?: null;
    }

    public function getName()
    {
        if($this->first_name && $this->last_name){
            return $this->first_name . " " . $this->last_name;
        }

        if($this->first_name){
            return $this->first_name;
        }

        return null;
    }

    public function getNameOrUsername()
    {
        $username = explode("@", $this->email)[0];
        return $this->getName() ?: $username;
    }

    public function getFirstNameOrUsername()
    {
        $username = explode("@", $this->email)[0];
        return $this->first_name ?: $username;
    }

    public function getAvatarUrl($size = 40)
    {
        return "https://www.gravatar.com/avatar/" . md5($this->email) . "?d=identicon&s=". $size;
    }

    public function skills()
    {
        return $this->belongsToMany('Coder\Models\Skill', 'users_skills', 'user_id', 'skill_id');
    }

    public function posts()
    {
        return $this->hasMany('Coder\Models\Post', 'user_id');
    }

    public function bookmarked()
    {
        return $this->belongsToMany('Coder\Models\Post', 'bookmarks', 'user_id', 'post_id');
    }

    public function statuses()
    {
        return $this->hasMany('Coder\Models\Status', 'user_id');
    }

    public function files()
    {
        return $this->hasMany('Coder\Models\File', 'user_id');
    }

    public function likes()
    {
        return $this->hasMany('Coder\Models\Like', 'user_id');
    }

    public function friendsOfMine()
    {
        return $this->belongsToMany("Coder\Models\User", 'friends', 'user_id', 'friend_id');
    }

    public function friendOf()
    {
        return $this->belongsToMany("Coder\Models\User", 'friends', 'friend_id', 'user_id');
    }

    public function friends()
    {
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()
        ->merge($this->friendOf()->wherePivot("accepted", true)->get());
    }

    public function friendRequests()
    {
        return $this->friendsOfMine()->wherePivot('accepted', false)->get();
    }

    public function friendRequestsPending()
    {
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    public function hasFriendRequestPending(User $user)
    {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }

    public function hasFriendRequestReceived(User $user)
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user)
    {
        $this->friendOf()->attach($user->id);
    }

    public function acceptFriendRequest(User $user)
    {
        $this->friendRequests()->where('id', $user->id)->first()->pivot
            ->update([
                "accepted" => true
                ]);
    }

    public function isFriendsWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }

    public function hasLikedStatus(Status $status)
    {
        /*return (bool) $status->likes->where('likeable_id', $status->id)
                    ->where('likeable_type', get_class($status))
                    ->where('user_id', $this->id)
                    ->count();*/
        return (bool) $status->likes->where('user_id', $this->id)->count();
    }

    public function hasBookmarkedPost(Post $post)
    {
        /*return (bool) $status->likes->where('likeable_id', $status->id)
                    ->where('likeable_type', get_class($status))
                    ->where('user_id', $this->id)
                    ->count();*/
        return (bool) $post->users_bookmarked->find($this->id);
    }

    public function hasLikedPost(Post $post)
    {
        /*return (bool) $status->likes->where('likeable_id', $status->id)
                    ->where('likeable_type', get_class($status))
                    ->where('user_id', $this->id)
                    ->count();*/
        return (bool) $post->likes->where('user_id', $this->id)->count();
    }

    public function permissions()
    {
        return $this->hasOne("Coder\Models\Permission", 'user_id');
    }

    public function hasPermission($permission)
    {
        return (bool) $this->permissions->{$permission};
    }

    public function isAdmin()
    {
        return $this->hasPermission('is_admin');
    }
    public function isSuperAdmin()
    {
        return $this->hasPermission('is_superadmin');
    }
}
