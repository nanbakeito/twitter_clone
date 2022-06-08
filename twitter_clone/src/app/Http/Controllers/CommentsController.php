<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;

class CommentsController extends Controller
{
    /**
     * ミドルウェアによるバリデーション
     */
    public function __construct() {
        $this->middleware('validationComment')->only(['store']);
    }

    /**
     * コメント新規投稿機能
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Comment  $comment
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Comment $comment)
    {
        $user = auth()->user();
        $commentData = $request->all();
        $comment->commentStore($user->id, $commentData);

        return back();
    }
}
