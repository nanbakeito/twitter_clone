<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tweet;
use App\Models\Comment;
use App\Models\Follower;

class TweetsController extends Controller
{
    /**
     * tweet一覧機能
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index(Tweet $tweet, Follower $follower)
    {
        $user = auth()->user();
        $followIds = $follower->followingIds($user->id);
        // followed_idだけ抜き出す
        $followingIds = $followIds->pluck('followed_id')->toArray();

        $timelines = $tweet->getTimelines($user->id, $followingIds);

        return view('tweets.index', [
            'user'      => $user,
            'timelines' => $timelines
        ]);
    }

    /**
     * tweet詳細機能
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Tweet $tweet, Comment $comment)
    {
        $user = auth()->user();
        $tweet = $tweet->getTweet($tweet->id);
        $comments = $comment->getComments($tweet->id);

        return view('tweets.show', [
            'user'     => $user,
            'tweet' => $tweet,
            'comments' => $comments
        ]);
    }

    /**
     * tweet新規投稿画面を表示する機能
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();

        return view('tweets.create', [
            'user' => $user
        ]);
    }

    /**
     * tweet新規投稿機能
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tweet $tweet)
    {
        $user = auth()->user();
        $data = $request->all();
        $tweet->tweetStore($user->id, $data);

        return redirect('tweets');
    }

    /**
     * ミドルウェアによるバリデーション
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->middleware('validationTweet')->only(['store']);
    }
}
