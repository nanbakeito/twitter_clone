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
    public function fetchFollowCount(int $userId)
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
    public function fetchFollowerCount(int $userId)
    {
        return $this->where('followed_id', $userId)->count();
    }

    /**
     * 該当ユーザIDを引数で渡してフォローしているユーザIDを取得します
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollowingIds(int $userId)
    {
        if ($this->fetchFollowCount($userId) > 0) {
            $followings = $this->where('following_id', $userId)->get();
            foreach ($followings as $following) {
                $followingIds[] = $following->followed_id;
            }

            return $followingIds;
        } else {
            return $followingIds = [];
        }
    }

    /**
     * 該当ユーザIDを引数で渡してフォローされているユーザIDを取得します
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollowerIds(int $userId)
    {
        if ($this->fetchFollowerCount($userId) > 0) {
            $followers = $this->where('followed_id', $userId)->get();
            foreach ($followers as $follower) {
                $followerIds[] = $follower->following_id;
            }

            return $followerIds;
        } else {     
            return $followerIds = [];
        }
    }
}
