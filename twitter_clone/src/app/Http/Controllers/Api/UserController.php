<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    /**
     * ユーザータイムライン絞り込み
     *
     * @param  Illuminate\Http\Request $Request
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function sortUserTimeLines(Request $request, User $user, Follower $follower)
    {
        $loginUserId = auth()->user()->id;
        // Vueから送られたチェックリスト（この配列の中身を見て絞り込みを行う）
        $checkList = array_map('intval', $request->checkList);
        $followingIds = $follower->fetchFollowingIds($loginUserId);
        $followerIds = $follower->fetchFollowerIds($loginUserId);
        $userIds = $user->setUserIds($loginUserId, $checkList, $followingIds, $followerIds);
        $userTimeLines = $user->fetchUsers($userIds);

        return response()->json($userTimeLines); 
    }

    /** 
     * ユーザータイムライン取得（自身以外）
     * 
     * @param  User  $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchUserTimeLines(User $user)
    {
        $userIds = $user->fetchAllUserIds();
        $userTimeLines = $user->fetchUsers($userIds);

        return response()->json($userTimeLines); 
    }
}
