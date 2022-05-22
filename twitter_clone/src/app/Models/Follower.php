<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    protected $primaryKey = [
        'following_id',
        'followed_id'
    ];
    
    protected $fillable = [
        'following_id',
        'followed_id'
    ];

    public $timestamps = false;

    public $incrementing = false;

    /**
     * フォロー数カウント
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowCount(int $userId)
    {
        return $this->where('following_id', $userId)->count();
    }

    /**
     * フォロワー数カウント
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowerCount(int $userId)
    {
        return $this->where('followed_id', $userId)->count();
    }

    /**
     * ログインしているユーザIDを引数で渡してフォローしているユーザIDを取得します
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function followingIds(int $userId)
    {
        return $this->where('following_id', $userId)->get('followed_id');
    }
}
