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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFollowCount($user_id)
    {
        return $this->where('following_id', $user_id)->count();
    }

    /**
     * フォロワー数カウント
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getFollowerCount($user_id)
    {
        return $this->where('followed_id', $user_id)->count();
    }

    /**
     * ログインしているユーザIDを引数で渡してフォローしているユーザIDを取得します
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function followingIds(int $user_id)
    {
        return $this->where('following_id', $user_id)->get('followed_id');
    }
}
