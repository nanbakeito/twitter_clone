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
    public function fetchFollow(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followingIds = $follower->followingIds($userId);
        $data = $user->getFollower($followingIds);

        return response()->json($data); 
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
    public function fetchFollower(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followerIds = $follower->followerIds($userId);
        $data = $user->getFollower($followerIds);

        return response()->json($data); 
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
    public function narrowDownUserTimeLines(Request $Request,User $user, Follower $follower)
    {
        $checkList = array_map('intval', $Request->checkList);
        $followingIds = $follower->followingIds($Request->user_id);
        $followerIds = $follower->followerIds($Request->user_id);
        $userIds = $user->fetchUserIdsByRequest($checkList, $followingIds, $followerIds);
        $userTimeLines = $user->fetchUserTimeLines($userIds, $Request->user_id);

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
