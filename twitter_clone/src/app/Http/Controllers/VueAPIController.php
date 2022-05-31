<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follower;
use App\Models\Comment;

class VueAPIController extends Controller
{
    /**
     * フォローしているユーザー取得
     *
     * @param  User  $user
     * @param  Follower  $follower
     * 
     * @return \Illuminate\Http\Response
     */
    public function getFollowing(User $user, Follower $follower)
    {
        $userId = "1";
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
    public function getFollower(User $user, Follower $follower)
    {
        $userId = "1";
        $followerIds = $follower->followerIds($userId)->toArray();
        if (isset($followerIds)) {
            $followerIds = $follower->followerIds($userId)->toArray();
            $data = $user->getFollower($followerIds);

            return response()->json($data); 
        }
    }

    /**
     * コメント投稿 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function commentPost(Request $request, Comment $comment)
    {
        $data = $request->all();
        $comment->commentStore($data);
        
        return response()->json($data["text"]); 
    }

    /**
     * コメント削除 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function commentDelete(Request $request, Comment $comment)
    {
        $commentId = $request->id;
        $comment->commentDelete($commentId);
        
        return response()->json("削除"); 
    }

    /**
     * コメント一覧取得
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * @param  User     $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function commentGet(Request $request, Comment $comment, User $user)
    {
        $data = $request->all();
        $comments = $comment->comments($data["tweet"]);

        foreach ($comments as $value) {
            $commentUser = $user->where('id', $value->user_id)->first();
            $userName = $commentUser->name;
            $userId = $commentUser->id;
            $userProfileImage = $commentUser->profile_image;
            $commentList = ([
                'id'                => $value->id,
                'userName'          => $userName,
                'userId'            => $userId,
                'userProfileImage'  => $userProfileImage,
                'text'              => $value->text,
                'createdAt'         => $value->created_at->format('Y-m-d H:i'),
            ]);
            $commentLists[] = $commentList;
        }

        return response()->json($commentLists); 
    }
}
