<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function loadRelationshipCounts()
    {
        $this->loadCount(['microposts','followings','followers','favorites']);
    }
   
    /**
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */ 
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
            ##belongsToMany(関係先のモデル,中間テーブル,中間テーブルに保存されている自分のIDを示すカラム,関係先のID)
    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
      public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする  pluck() は引数として与えられたテーブルのカラムの値だけを抜き出す命令です。== users tableのidを抜き出す
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    public function is_favorited($micropostId)
    {
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
    }
    
    public function addFavorite($micropostId)
    {
        // すでにフォローしているかの確認
        $favoriteExists = $this->is_favorited($micropostId);
        
        if ($favoriteExists) {
        } else {
            $this->favorites()->attach($micropostId);
        }
    }
    
    public function deleteFavorite($micropostId)
    {
        $favoriteExists = $this->is_favorited($micropostId);
        if ($favoriteExists) {
           $this->favorites()->detach($micropostId);
        } 
        else {
        }
    }
   
    public function favorite_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする  pluck() は引数として与えられたテーブルのカラムの値だけを抜き出す命令です。== users tableのidを抜き出す
        $micropostIds = $this->favorites()->pluck('microposts.id')->toArray();
        return Micropost::whereIn('id', $micropostIds);
    }
    
}