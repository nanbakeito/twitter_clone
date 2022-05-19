<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Tweet;
use App\Models\Comment;
use App\Models\Follower;

class TweetsController extends Controller
{
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
}
