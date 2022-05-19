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
     * ユーザー一覧機能
     *
     * @param  int  $id
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
     * @return  \Illuminate\Http\RedirectResponse
    */
    public function follow(Request $request)
    {
        $follower = auth()->user();
        $follower->follow($request->input('id'));
        return back();
    }

    /** フォロー解除機能
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\RedirectResponse
    */
    public function unfollow(Request $request)
    {
        $follower = auth()->user();
        $follower->unFollow($request->input('id'));
        return back();
    }

    /**
     * ユーザー詳細画面
     *
     * @param  int  $id
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
     * @param  int  $id
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $user->updateProfile($data);
        
        return redirect('users/'.$user->id);
    }

    public function __construct() {
        $this->middleware('validationUser')->only(['update']);
    }
}
