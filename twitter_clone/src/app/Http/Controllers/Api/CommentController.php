<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comment;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     */
    public function __construct() {
        $this->middleware('validationComment')->only(['store']);
    }
    
    /**
     * 
     * コメント投稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function postComment(Request $request, Comment $comment)
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
    public function deleteComment(Request $request, Comment $comment)
    {
        $commentId = $request->id;
        $comment->commentDelete($commentId);
        
        return response()->json("削除"); 
    }

    /**
     * 
     * コメント一覧取得
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * @param  User     $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function getComment(Request $request, Comment $comment, User $user)
    {
        $data = $request->all();
        $commentLists = $comment->fetchCommentsByTweetId($data["tweet_id"]);

        if ($commentLists) {
            foreach ($commentLists as $commentList) {
                $commentUser = $user->where('id', $commentList->user_id)->first();
                $commentFeatures = ([
                    'id'                => $commentList->id,
                    'text'              => $commentList->text,
                    'createdAt'         => $commentList->created_at->format('Y-m-d H:i'),
                    'userId'            => $commentUser->id,
                    'userName'          => $commentUser->name,
                    'userProfileImage'  => $commentUser->profile_image,
                ]);
                $commentFeaturesList[] = $commentFeatures;
            }

            return response()->json($commentFeaturesList); 
        } else {
            $commentFeaturesList = [] ;
            
            return response()->json($commentFeaturesList); 
        }
    }
}
