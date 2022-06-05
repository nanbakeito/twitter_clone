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
        'tweet_id',
        'user_id',
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
     * @param  array  $data
     * 
     * @return void
     */
    public function commentStore(Array $data) : void
    {
        $this->user_id = $data['user_id'];
        $this->tweet_id = $data['tweet_id'];
        $this->text = $data['text'];
        $this->save();
    }

    /**
     * コメント削除
     *
     * @param  int $id
     * 
     * @return void
     */
    public function commentDelete(int $id) : void
    {
        $this->where('id',$id)->delete();
    }

    /**
     * 一意のツイートの全コメント取得
     *
     * @param  int $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchCommentsByTweetId(int $tweetId)
    {
        return $this->where('tweet_id', $tweetId)->exists() ? $this->where('tweet_id', $tweetId)->get() : null ;
    }
}
