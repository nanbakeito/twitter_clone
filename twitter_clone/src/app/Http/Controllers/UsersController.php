<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Tweet;
use App\Models\Follower;

class UsersController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     *
     * @return \Illuminate\Http\Response
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
        $allUsers = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'allUsers'  => $allUsers
        ]);
    }

    /** フォロー機能
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * 
     * @return  \Illuminate\Http\RedirectResponse
    */
    public function follow(Request $request, User $user)
    {
        $currentUser = $user->where('id', $request->userId)->first();
        $currentUser->follow($request->input('id'));

        return back();
    }

    /** フォロー解除機能
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * 
     * @return  \Illuminate\Http\RedirectResponse
    */
    public function unfollow(Request $request, User $user)
    {
        $currentUser = $user->where('id', $request->currentUserId)->first();
        $currentUser->unFollow($request->input('id'));

        return back();
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
        $timelines = $tweet->getUserTimeLine($user->id);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'timelines'      => $timelines,
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
        $data = $request->all();
        $user->updateProfile($data);
        
        return redirect('users/'.$user->id);
    }
}
