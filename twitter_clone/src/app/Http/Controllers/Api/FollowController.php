<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Follower;
use App\Http\Controllers\Controller;


class FollowController extends Controller
{
    /**
     * フォロー&解除機能
     * 
     * @param  Illuminate\Http\Request $Request
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function follow(Request $request, Follower $follower)
    {
        $loginUserId = $request->login_user_id; 
        $userId = $request->user_id;
        $alreadyFollowed =  $follower->where('following_id', $loginUserId)->where('followed_id', $userId)->first();
    
        if (!$alreadyFollowed) { 
            $follower->following_id = $loginUserId; 
            $follower->followed_id = $userId;
            $follower->save();

            return response(true); 
        } else { 
            $follower->where('following_id', $loginUserId)->where('followed_id', $userId)->delete();

            return response(false);
        }
    }
}
