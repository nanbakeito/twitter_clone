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
     * @param  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowCount($userId)
    {
        return $this->where('following_id', $userId)->count();
    }

    /**
     * フォロワー数カウント
     *
     * @param  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowerCount($userId)
    {
        return $this->where('followed_id', $userId)->count();
    }

    /**
     * 該当ユーザIDを引数で渡してフォローしているユーザIDを取得します
     *
     * @param  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function followingIds(int $userId)
    {
        if ($this->getFollowCount($userId) > 0) {
            return $this->where('following_id', $userId)->get('followed_id')->toArray();
        } else {
            return null;
        }
    }
    /**
     * 該当ユーザIDを引数で渡してフォローされているユーザIDを取得します
     *
     * @param  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function followerIds($userId)
    {
        if ($this->getFollowerCount($userId) > 0) {
            return $this->where('followed_id', $userId)->get('following_id')->toArray();
        } else {
            return null;
        }
    }
}
