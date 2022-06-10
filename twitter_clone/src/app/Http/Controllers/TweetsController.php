<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tweet;
use App\Models\Comment;

class TweetsController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     */
    public function __construct()  {
        $this->middleware('validationTweet')->only(['store','update']);
    }

    /**
     * tweet一覧機能
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        return view('tweets.index', [
            'user'      => $user,
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
        $tweet = $tweet->fetchTweet($tweet->id);
        $comments = $comment->fetchComments($tweet->id);

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
        $tweetData = $request->all();

        if (isset($tweetData['image'])) {
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
        $tweets = $tweet->fetchEditTweet($user->id, $tweet->id);

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
        $tweetData = $request->all();

        if (isset($tweetData['image'])) {
            $fileName = $tweetData['image']->store('public/image/');

            $tweet::where('id', $tweetData['id'])
            ->update([
                'user_id' => $tweetData['userId'],
                'text' => $tweetData['text'],
                'image' => basename($fileName),
            ]);
        } else {
            $tweet::where('id', $tweetData['id'])
            ->update([
                'user_id' => $tweetData['userId'],
                'text' => $tweetData['text'],
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
