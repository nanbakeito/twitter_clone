<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;

class VueAPIController extends Controller
{
   // indexアクションを定義（indexメソッドの定義と同義)
    public function getFollowing(User $user, Follower $follower)
    {
        $userId = "1";
        $followingIds = $follower->followingIds($userId);
        $data = $user->getFollowing($followingIds);

        return response()->json($data); 
    }

    public function getFollower(User $user, Follower $follower)
    {
        $userId = "1";
        $followerIds = $follower->followerIds($userId);
        $data = $user->getFollower($followerIds);

        return response()->json($data); 
    }
}
