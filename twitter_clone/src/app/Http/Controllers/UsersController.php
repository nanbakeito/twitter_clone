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
        // フォローしているかの確認
        $isFollowing = $follower->isFollowing($request->input('id'));
        if(!$isFollowing) {
            // フォローしていなければフォロー
            $follower->follow($request->input('id'));
            return back();
        }
        else{
            // フォローしていればフラッシュメッセージ
            return redirect('users')->with('flashMessage', 'すでにフォローしています');
        }
    }

    /** フォロー解除機能
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return  \Illuminate\Http\RedirectResponse
    */
    public function unfollow(Request $request)
    {
        $follower = auth()->user();
        // フォローしているかの確認
        $isFollowing = $follower->isFollowing($request->input('id'));
        if($isFollowing) {
            // フォローしていればフォロー解除
            $follower->unfollow($request->input('id'));
            return back();
        }
        else{
            // フォローしていなければフラッシュメッセージ
            return redirect('users')->with('flashMessage', 'フォローしていません');
        }
    }

    /**
     * ユーザー詳細画面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, Tweet $tweet, Follower $follower)
    {
        $loginUser = auth()->user();
        $isFollowing = $loginUser->isFollowing($user->id);
        $isFollowed = $loginUser->isFollowed($user->id);
        $timelines = $tweet->getUserTimeLine($user->id);
        $tweetCount = $tweet->getTweetCount($user->id);
        $followCount = $follower->getFollowCount($user->id);
        $followerCount = $follower->getFollowerCount($user->id);

        return view('users.show', [
            'user'           => $user,
            'isFollowing'   => $isFollowing,
            'isFollowed'    => $isFollowed,
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
        $validator = Validator::make($data, [
            'name'          => ['required', 'string', 'max:255'],
            'profile_image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'email'         => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
        ]);
        $validator->validate();
        $user->updateProfile($data);

        return redirect('users/'.$user->id);
    }
}
