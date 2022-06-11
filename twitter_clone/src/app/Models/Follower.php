<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        $followingIds = DB::table('followers')->where('following_id', $userId)->get('following_id');
        return $followingIds->count() > 0 ? $followingIds : [];
    
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
        $followerIds = DB::table('followers')->where('followed_id', $userId)->get('followed_id');
        
        return $followerIds->count() > 0 ? $followerIds : [];

    }
}
