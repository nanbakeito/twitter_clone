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
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowing(User $user, Follower $follower)
    {
        // 未実装
        $userId = "1";
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
    public function getFollower(User $user, Follower $follower)
    {
        // 未実装
        $userId = "1";
        $followerIds = $follower->followerIds($userId)->toArray();
        if (isset($followerIds)) {
            $followerIds = $follower->followerIds($userId)->toArray();
            $data = $user->getFollower($followerIds);

            return response()->json($data); 
        }
    }
}
