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
        $login_user = auth()->user();
        $isFollowing = $login_user->isFollowing($user->id);
        $isFollowed = $login_user->isFollowed($user->id);

        return view('users.show', [
            'user'           => $user,
            'is_following'   => $isFollowing,
            'is_followed'    => $isFollowed,
        ]);
    }
}
