<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
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
     * コメント投稿
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function postComment(Request $request, Comment $comment)
    {
        $commentData = $request->all();
        $comment->commentStore($commentData);
        
        return response()->json($commentData["text"]); 
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
     * コメント一覧表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchComment(Request $request, Comment $comment)
    {
        $commentData = $request->all();
        $comments = $comment->fetchCommentsByTweetId($commentData["tweet_id"]);
        $commentFeaturesList = isset($comments) ? $this->fetchCommentList($comments) : $commentFeaturesList= [];

        return response()->json($commentFeaturesList); 
    }

    /**
     * コメント一覧取得
     *
     * @param  array $comments
     * 
     * @return \Illuminate\Http\Response
     */
    public function fetchCommentList(object $comments)
    {
            foreach ($comments as $comment) {

                $commentFeatures = ([
                    'id'                => $comment->id,
                    'text'              => $comment->text,
                    'createdAt'         => $comment->created_at->format('Y-m-d H:i'),
                    'userId'            => $comment->user->id,
                    'userName'          => $comment->user->name,
                    'userProfileImage'  => $comment->user->profile_image,
                ]);
                $commentList[] = $commentFeatures;
            }

            return $commentList;
    }
}
