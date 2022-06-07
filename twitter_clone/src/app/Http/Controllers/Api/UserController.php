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
     * @param  Illuminate\Http\Request $request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollow(Request $request,User $user, Follower $follower)
    {
        $userId = $request->user_id;
        $followingIds = $follower->followingIds($userId);
        
        if (isset($followingIds)) {
            $followingIds = $follower->followingIds($userId)->toArray();
            $data = $user->getFollower($followingIds);

            return response()->json($data); 
        }
    }

    /**
     * フォロワー取得
     *
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollower(Request $request,User $user, Follower $follower)
    {
        $userId = $request->user_id;
        $followerIds = $follower->followerIds($userId);

        if (isset($followerIds)) {
            $followerIds = $follower->followerIds($userId)->toArray();
            $data = $user->getFollower($followerIds);

            return response()->json($data); 
        }
    }

    /**
     * ユーザータイムライン絞り込み
     *
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function narrowDownUserTimeLinesByRequest(Request $request,User $user, Follower $follower)
    {
        $judge = array_map('intval', $request->checkList);
        $followingIds = $follower->followingIds($request->user_id);
        $followerIds = $follower->followerIds($request->user_id);
        
        if (isset($followingIds) and isset($followerIds)) {
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);
        
        } elseif (isset($followingIds)) {
            $followerIds = [];
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);

        } elseif (isset($followerIds)) {
            $followingIds = [];
            $userIds = $user->fetchUserIdsByRequest($judge, $request->user_id, $followingIds, $followerIds);

        }

        $userTimeLines = $user->fetchUserTimeLines($userIds, $request->user_id);

        return response()->json($userTimeLines); 
    }

    /** 
     * ユーザータイムライン取得（自身以外）
     * 
     * @param  Illuminate\Http\Request $request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimeLines(Request $request,User $user)
    {
        $userIds = $user->fetchUserIds();
        $userTimeLines = $user->fetchUserTimeLines($userIds, $request->user_id);
    
        return response()->json($userTimeLines); 
    }
}
