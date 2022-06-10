<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Follower;
use App\Models\Tweet;
use App\Models\User;
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
     * @param  Illuminate\Http\Request $request
     * @param  Follower  $follower
     * @param  Tweet     $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchTimeLine(Request $request, Follower $follower, Tweet $tweet)
    {
        $followingIds = $follower->fetchFollowingIds($request->user_id);
        $timeLine = $tweet->fetchTimeLine($request->user_id, $followingIds);

        return response()->json($timeLine);
    }

    /**
     * タイムライン絞り込み
     * 
     * @param  Illuminate\Http\Request $request
     * @param  Follower  $follower
     * @param  Tweet     $tweet
     * @param  User      $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function sortTimeLine(Request $request, Follower $follower, Tweet $tweet, User $user)
    {
        $userId = $request->user_id;
        // Vueから送られたチェックリスト（この配列の中身を見て絞り込みを行う）
        $checkList = array_map('intval', $request->checkList);
        $followingIds = $follower->fetchFollowingIds($userId);
        $followerIds = $follower->fetchFollowerIds($userId);
        $userIds = $user->setUserIds($userId, $checkList, $followingIds, $followerIds);
        $timeLine = $tweet->fetchTimeLine($userId, $userIds);
        
        return response()->json($timeLine);
    }

    /**
     * tweet投稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function postTweet(Request $request, Tweet $tweet)
    {
        $loginUserId = auth()->user()->id;
        $tweetData = $request->all();
        $tweet->saveTweet($tweetData);
        $userIds[] = $loginUserId;
        $tweetInfo = $tweet->fetchTweetInfo();
        
        return response()->json($tweetInfo);
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
    public function favorite(Request $request, Favorite $favorite)
    {
        $loginUserId = auth()->user()->id; 
        $tweetId = $request->tweet_id; 
        $favorite->favorite($loginUserId, $tweetId);
        
        return response()->json(); 
    }
}
