<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Tweet extends Model
{

    /**
     * Mass Assignment 可能
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'image',
        'user_id'
    ];
    
    /**
     * usersテーブルとのリレーションを定義する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * favoritesテーブルとのリレーションを定義する
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * commentsテーブルとのリレーションを定義する
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * いいねされているかを判定するメソッド
     *
     * @param  int  $userId
     * 
     * @return  bool
     */
    public function isLikedBy(int $userId)
    {
        return Favorite::where('user_id', $userId)->where('tweet_id', $this->id)->first() !==null;
    }
    
    /**
     * タイムライン情報取得しページネイト
     *
     * @param  int  $userId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimeLine(int $userId)
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
    public function fetchTweetCount(int $userId)
    {
        return $this->where('user_id', $userId)->count();
    }

    /**
     * フォローしているuserのtimeline作成
     *
     * @param  int  $userId
     * @param  array  $followingIds
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchTimeLine(int $userId, array $userIds)
    {
            $tweets = $this->whereIn('user_id', $userIds)->orderBy('created_at', 'DESC')->get();
            $timeLine = [];
            foreach($tweets as $tweet) {
                $tweetInfo = ([
                    'id'                    => $tweet->id,
                    'text'                  => $tweet->text,
                    'image'                 => $tweet->image,
                    'createdAt'             => $tweet->created_at->format('Y-m-d H:i'),
                    'commentCount'          => count($tweet->comments),
                    'favoriteCount'         => count($tweet->favorites),
                    'alreadyFavorite'       => $tweet->isLikedBy($userId),
                    // ユーザー情報（リレーション）
                    'userId'                => $tweet->user->id,
                    'userName'              => $tweet->user->name,
                    'userProfileImage'      => $tweet->user->profile_image,
                ]);
                $timeLine[] = $tweetInfo;
            }

        return $timeLine;
    }

    /**
     * 投稿した際の自らのtweet情報取得
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchTweetInfo()
    {
        $loginUserId = auth()->user()->id;
        $tweet = $this->where('user_id', $loginUserId)->orderBy('created_at', 'DESC')->first();
        $tweetInfo = ([
            'id'                    => $tweet->id,
            'text'                  => $tweet->text,
            'image'                 => $tweet->image,
            'createdAt'             => $tweet->created_at->format('Y-m-d H:i'),
            'commentCount'          => count($tweet->comments),
            'favoriteCount'         => count($tweet->favorites),
            'alreadyFavorite'       => $tweet->isLikedBy($loginUserId),
            // ユーザー情報（リレーション）
            'userId'                => $tweet->user->id,
            'userName'              => $tweet->user->name,
            'userProfileImage'      => $tweet->user->profile_image,
        ]);

        return $tweetInfo;
    }

    /**
     * 詳細画面
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchTweet(int $tweetId)
    {
        return $this->with('user')->where('id', $tweetId)->first();
    }

    /**
     * user取り出し
     *
     * @param  int  $userId
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchEditTweet(int $userId, int $tweetId)
    {
        return $this->where('user_id', $userId)->where('id', $tweetId)->first();
    }

    /**
     * tweet削除
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function tweetDelete(int $tweetId) 
    {
        $tweet = $this->where('id', $tweetId)->first();
        $tweet->favorites()->delete();
        $tweet->delete();
        return $tweet;
    }

    /**
     * いいねカウント
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function favoriteCount(int $tweetId)
    {
        return $this->favorites()->where('tweet_id', $tweetId)->count();
    }

    /**
     * tweet投稿
     *
     * @param  array $tweetData
     * 
     * @return  void
     */
    public function saveTweet(array $tweetData) : void
    {
        if (!in_array('null', $tweetData)) {
            $fileName = $tweetData['image']->store('public/image/');

            Tweet::create([
                'user_id' => $tweetData['userId'],
                'text' => $tweetData['text'],
                'image' => basename($fileName),
            ]);
            
        } else {
            Tweet::create([
                'user_id' => $tweetData['userId'],
                'text' => $tweetData['text'],
            ]);
        };
    }
}
