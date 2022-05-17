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
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $all_users = $user->getAllUsers(auth()->user()->id);

        return view('users.index', [
            'all_users'  => $all_users
        ]);
    }


    /** フォロー機能
     * 
     * @param  \Illuminate\Http\Request  $request
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
    }

    /** フォロー解除機能
     * 
     * @param  \Illuminate\Http\Request  $request
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
