<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * Mass Assignment 可能
     *
     * @var array
     */
    protected $fillable = [
        'text'
    ];

    /**
     *　usersテーブルとのリレーション（１対１）
     *
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 詳細画面
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getComments(int $tweetId)
    {
        return $this->with('user')->where('tweet_id', $tweetId)->get();
    }
}
