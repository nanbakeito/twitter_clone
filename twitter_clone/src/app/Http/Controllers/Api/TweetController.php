<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;
use App\Models\Tweet;
use App\Models\Favorite;
use App\Http\Controllers\Controller;


class TweetController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     */
    public function __construct()  {
        $this->middleware('validationTweet')->only(['postTweet']);
    }

    /**
     * タイムライン表示
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  Follower  $follower
     * @param  Tweet     $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchTimeLines(Request $request, Follower $follower, Tweet $tweet)
    {
        $followingIds = $follower->followingIds($request->user_id);
        if(isset($followingIds)) {
            $timeLines = $tweet->fetchTimeLines($request->user_id, $followingIds);
        } else {
            $timeLines = null;
        }
        return response()->json($timeLines);
    }

    /**
     * タイムライン絞り込み
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  Follower  $follower
     * @param  Tweet     $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function narrowDownTimeLine(Request $request, Follower $follower, Tweet $tweet, User $user)
    {
        $judge = array_map('intval', $request->checkList);
        $followingIds = $follower->followingIds($request->user_id);
        $followerIds = $follower->followerIds($request->user_id);

        if (isset($followingIds) && isset($followerIds)) {
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);
        
        } elseif (isset($followingIds)) {
            $followerIds = [];
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);

        } elseif (isset($followerIds)) {
            $followingIds = [];
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);

        } else {
            $followingIds = [];
            $followerIds = [];
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);
        }
        
        $timeLines = $tweet->fetchTimeLines($request->user_id, $userIds);
        
        return response()->json($timeLines);
    }

    /**
     * tweet投稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Follower  $follower
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function postTweet(Request $request, Follower $follower, Tweet $tweet)
    {
        $data = $request->all();

        if (!in_array('null', $data)) {
            $fileName = $data['image']->store('public/image/');

            Tweet::create([
                'user_id' => $data['userId'],
                'text' => $data['text'],
                'image' => basename($fileName),
            ]);
            
        } else {
            Tweet::create([
                'user_id' => $data['userId'],
                'text' => $data['text'],
            ]);
        };

        return response()->json();
    }

    /**
     * tweet削除
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteTweet(Request $request, Tweet $tweet)
    {
        $tweetId = $request->id;
        $tweet->tweetDelete($tweetId);
        
        return response()->json(); 
    }

    /**
     * いいね
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function favorite(Request $request)
    {
        $loginUserId = $request->login_user_id; 
        $tweetId = $request->tweet_id; 
        $alreadyFavorite = Favorite::where('user_id', $loginUserId)->where('tweet_id', $tweetId)->first();
    
        // ユーザーがいいねを押していない場合
        if (!$alreadyFavorite) { 
            $favorite = new Favorite; 
            $favorite->tweet_id = $tweetId; 
            $favorite->user_id = $loginUserId;
            $favorite->save();
            $judge = true;

        } else { 
            Favorite::where('tweet_id', $tweetId)->where('user_id', $loginUserId)->delete();
            $judge = false;
        }
        
        $tweetFavoritesCount = Tweet::withCount('favorites')->findOrFail($tweetId)->favoriteCount($tweetId);
        $param = [
            'tweetFavoritesCount' => $tweetFavoritesCount,
            'judge'               => $judge,
        ];
        return response()->json($param); 
    }
}
