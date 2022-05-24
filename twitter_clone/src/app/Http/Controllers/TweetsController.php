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
     * ミドルウェアによるバリデーション
     */
    public function __construct() {
        $this->middleware('validationTweet')->only(['store','update']);
    }

    /**
     * tweet一覧機能
     *
     * @param  Tweet  $tweet
     * @param  Follower  $follower
     * 
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
     * @param  Tweet  $tweet
     * @param  Comment  $comment
     * 
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
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Tweet $tweet)
    {
        $data = $request->all();

        if (isset($data['image'])) {
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

        return redirect('tweets');
    }

    /**
     * tweet編集画面
     *
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Tweet $tweet)
    {
        $user = auth()->user();
        $tweets = $tweet->getEditTweet($user->id, $tweet->id);

        if (!isset($tweets)) {
            return redirect('tweets');
        }

        return view('tweets.edit', [
            'user'   => $user,
            'tweets' => $tweets
        ]);
    }

    /**
     * tweet更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tweet $tweet)
    {
        $data = $request->all();

        if (isset($data['image'])) {
            $fileName = $data['image']->store('public/image/');

            $tweet::where('id', $data['id'])
            ->update([
                'user_id' => $data['userId'],
                'text' => $data['text'],
                'image' => basename($fileName),
            ]);
        } else {
            $tweet::where('id', $data['id'])
            ->update([
                'user_id' => $data['userId'],
                'text' => $data['text'],
            ]);
        };

        return redirect('tweets');
    }

    /**
     * tweet削除
     *
     * @param  Tweet  $tweet
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tweet $tweet)
    {
        $user = auth()->user();
        $tweet->tweetDestroy($user->id, $tweet->id);

        return back();
    }
}
