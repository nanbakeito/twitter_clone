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
     *　usersテーブルとのリレーション（多対1）
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

    /**
     * コメント保存
     *
     * @param  int  $user_id
     * @param  array  $data
     * 
     * @return void
     */
    public function commentStore(int $user_id, Array $data) : void
    {
        $this->user_id = $user_id;
        $this->tweet_id = $data['tweet_id'];
        $this->text = $data['text'];
        $this->save();
    }
}
