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
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getUserTimeLine(int $userId)
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->paginate(50);
    }

    /**
     * ツイート数カウント
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTweetCount(int $userId)
    {
        return $this->where('user_id', $userId)->count();
    }

    /**
     * フォローしているuserのtimeline作成
     *
     * @param  int  $userId
     * @param  array  $followIds
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTimeLines(int $userId, Array $followIds)
    {
        // 自身とフォローしているユーザIDを結合する
        $followIds[] = $userId;
        $timelines = $this->whereIn('user_id', $followIds)->orderBy('created_at', 'DESC')->paginate(50);
        // ツイートがあるかどうか
        return $this->wherein('user_id', $followIds)->exists() ? $timelines : null ;
    }

    /**
     * 詳細画面
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getTweet(int $tweetId)
    {
        return $this->with('user')->where('id', $tweetId)->first();
    }

    /**
     * 新規tweet保存
     *
     * @param  int  $userId
     * @param  array  $data
     * 
     * @return \Illuminate\Http\Response
     */
    public function tweetStore(int $userId, Array $data)
    {
        $this->user_id = $userId;
        $this->text = $data['text'];
        $this->save();

        return;
    }

    /**
     * 新規tweet保存
     *
     * @param  int  $userId
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getEditTweet(int $userId, int $tweetId)
    {
        return $this->where('user_id', $userId)->where('id', $tweetId)->first();
    }

    /**
     * tweet更新
     *
     * @param  int  $tweetId
     * @param  array  $data
     * 
     * @return \Illuminate\Http\Response
     */
    public function tweetUpdate(int $tweetId, Array $data)
    {
        $this->id = $tweetId;
        $this->text = $data['text'];
        $this->update();
    }

    /**
     * tweet削除
     *
     * @param  int  $userId
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function tweetDestroy(int $userId, int $tweetId)
    {
        return $this->where('user_id', $userId)->where('id', $tweetId)->delete();
    }
}
