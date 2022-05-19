<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Tweet extends Model
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * タイムライン情報取得しページネイト
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getUserTimeLine(int $user_id)
    {
        return $this->where('user_id', $user_id)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * ツイート数カウント
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTweetCount(int $user_id)
    {
        return $this->where('user_id', $user_id)->count();
    }

    /**
     * フォローしているuserのtimeline作成
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTimeLines(int $user_id, Array $followIds)
    {
        // 自身とフォローしているユーザIDを結合する
        $followIds[] = $user_id;
        return $this->whereIn('user_id', $followIds)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * 詳細画面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getTweet(int $tweet_id)
    {
        return $this->with('user')->where('id', $tweet_id)->first();
    }

    /**
     * 新規tweet保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tweetStore(Int $user_id, Array $data)
    {
        $this->user_id = $user_id;
        $this->text = $data['text'];
        $this->save();

        return;
    }

    /**
     * 新規tweet保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getEditTweet(int $user_id, int $tweet_id)
    {
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->first();
    }

    /**
     * tweet更新
     *
     * @param  int  $tweet_id
     * @return \Illuminate\Http\Response
     */
    public function tweetUpdate(int $tweet_id, Array $data)
    {
        $this->id = $tweet_id;
        $this->text = $data['text'];
        $this->update();

        return;
    }

    /**
     * tweet削除
     *
     * @param  int  $user_id,$tweet_id
     * @return \Illuminate\Http\Response
     */
    public function tweetDestroy(int $user_id, int $tweet_id)
    {
        return $this->where('user_id', $user_id)->where('id', $tweet_id)->delete();
    }
}
