<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;

class UsersController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     */
    public function __construct() {
        $this->middleware('validationUser')->only(['store','update']);
    }

    /**
     * ユーザー一覧機能
     *
     * @param  User  $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $allUsers = $user->fetchAllUsers(auth()->user()->id);

        return view('users.index', [
            'allUsers'  => $allUsers
        ]);
    }

    /** 
     * フォロー&解除機能　（Ajax）
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Follower $follower
     * 
     * @return  \Illuminate\Http\Response
    */
    public function follow(Request $request, Follower $follower)
    {
        $loginUserId = Auth()->user()->id; 
        $userId = $request->userId;
        $alreadyFollowed =  $follower->where('following_id', $loginUserId)->where('followed_id', $userId)->first();

        if (!$alreadyFollowed) { 
            $follower->following_id = $loginUserId; 
            $follower->followed_id = $userId;
            $follower->save();

        } else { 
            $follower->where('following_id', $loginUserId)->where('followed_id', $userId)->delete();
        }

        $followerCount = $follower->fetchFollowerCount($userId);
        $param = array('followerCount'=> $followerCount);
        
        return response()->json($param); 
    }

    /**
     * ユーザー詳細画面
     *
     * @param  User  $user
     * @param  Tweet  $tweet
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $timelines = $tweet->fetchUserTimeLine($user->id);
        $tweetCount = $tweet->fetchTweetCount($user->id);
        $followCount = $follower->fetchFollowCount($user->id);
        $followerCount = $follower->fetchFollowerCount($user->id);

        return view('users.show', [
            'user'          => $user,
            'timelines'     => $timelines,
            'tweetCount'    => $tweetCount,
            'followCount'   => $followCount,
            'followerCount' => $followerCount
        ]);
    }

    /**
     * ユーザー編集画面
     *
     * @param  User  $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * ユーザー更新機能
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $userData = $request->all();
        $user->updateProfile($userData);
        
        return redirect('users/'.$user->id);
    }
}
