<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * 
     * フォローしているユーザー取得
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollow(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followingIds = $follower->followingIds($userId);
        
        if (isset($followingIds)) {
            $followingIds = $follower->followingIds($userId)->toArray();
            $data = $user->getFollower($followingIds);

            return response()->json($data); 
        }
    }

    /**
     * 
     * フォロワー取得
     *
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchFollower(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followerIds = $follower->followerIds($userId);
        if (isset($followerIds)) {
            $followerIds = $follower->followerIds($userId)->toArray();
            $data = $user->getFollower($followerIds);

            return response()->json($data); 
        }
    }

    /**
     * 
     * ユーザータイムライン絞り込み
     *
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function narrowDownUserTimeLinesByRequest(Request $Request,User $user, Follower $follower)
    {
        $userId = $Request->user_id;
        $followerIds = $follower->followerIds($userId);
        if (isset($followerIds)) {
            $followerIds = $follower->followerIds($userId)->toArray();
            $data = $user->getFollower($followerIds);

            return response()->json($data); 
        }
    }


    /**
     * 
     * ユーザータイムライン取得（自身以外）
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
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
