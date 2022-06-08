<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * フォローしているユーザー取得
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollowingUser(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followingIds = $follower->followingIds($userId);
        $following = $user->getFollower($followingIds);

        return response()->json($following); 
    }

    /**
     * フォロワー取得
     *
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollowedUser(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followerIds = $follower->followerIds($userId);
        $follwers = $user->getFollower($followerIds);

        return response()->json($follwers); 
    }

    /**
     * ユーザータイムライン絞り込み
     *
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function sortUserTimeLines(Request $Request,User $user, Follower $follower)
    {
        $checkList = array_map('intval', $Request->checkList);
        $followingIds = $follower->followingIds($Request->user_id);
        $followerIds = $follower->followerIds($Request->user_id);
        $userIds = $user->fetchUserIds($checkList, $followingIds, $followerIds);
        $userTimeLines = $user->fetchUserTimeLines($userIds);

        return response()->json($userTimeLines); 
    }

    /** 
     * ユーザータイムライン取得（自身以外）
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimeLines(Request $Request,User $user)
    {
        $userIds = $user->fetchUserIds();
        $userTimeLines = $user->fetchUserTimeLines($userIds, $Request->user_id);
    
        return response()->json($userTimeLines); 
    }
}
