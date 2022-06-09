<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = false;

    /**
     * usersテーブルとのリレーションを定義する
     */
    public function user()
    {   
        return $this->belongsTo(User::class);
    }

    /**
     * tweetsテーブルとのリレーションを定義する
     */
    public function review()
    {   
        return $this->belongsTo(Tweet::class);
    }

    /**
     * tweetsテーブルとのリレーションを定義する
     * 
     * @param  int  $loginUserId
     * @param  int  $tweetId
     * 
     * @return  void
     */
    public function favorite(int $loginUserId, int $tweetId) : void
    {   
        $alreadyFavorite = $this->where('user_id', $loginUserId)->where('tweet_id', $tweetId)->first();

        if (!$alreadyFavorite) { 
            $favorite = new Favorite; 
            $favorite->tweet_id = $tweetId; 
            $favorite->user_id = $loginUserId;
            $favorite->save();

        } else { 
            Favorite::where('tweet_id', $tweetId)->where('user_id', $loginUserId)->delete();
        }
    }
}
