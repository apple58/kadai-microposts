<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */

    protected $hidden = ['password', 'remember_token'];

    /** 9.で追加 */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }

    /** 12.で追加 */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'user_favorite', 'user_id', 'micropost_id')->withTimestamps();
    }

    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {

            // 既にフォローしていれば何もしない

            return false;

        } else {

            // 未フォローであればフォローする

            $this->followings()->attach($userId);

            return true;
        }
    }

    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 自分自身ではないかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    public function favorite($micropostId)
    {
        // 既にお気に入りにしているかの確認
        $exist = $this->is_favorites($micropostId);

        if ($exist) {
            // 既にお気に入りにしていれば何もしない
            return false;
        } else {
            // 未だお気に入りにしていなければお気に入りにする
            $this->favorites()->attach($micropostId);
            return true;
        }
        
    }

    public function unfavorite($micropostId)
    {
        // 既にお気に入りにしているかの確認
        $exist = $this->is_favorites($micropostId);
        // ↓ 必要ない？
        if ($exist) {
            // 既にお気に入りにしていればお気に入りを外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // 未だお気に入りにしていなければ何もしない
            return false;
        }
    }
    
    public function is_favorites($micropostId) {
        // select * from microposts where user_id = $this->id;
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }

}
