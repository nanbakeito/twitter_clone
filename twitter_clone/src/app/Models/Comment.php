<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * Mass Assignment 可能
     *
     * @var array
     */
    protected $fillable = [
        'tweet_id',
        'user_id',
        'text'
    ];

    /**
     *　usersテーブルとのリレーション（多対1）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 詳細画面
     *
     * @param  int  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function getComments(int $tweetId)
    {
        return $this->with('user')->where('tweet_id', $tweetId)->get();
    }

    /**
     * コメント保存
     *
     * @param  array  $data
     * 
     * @return void
     */
    public function commentStore(Array $data) : void
    {
        $this->user_id = $data['user'];
        $this->tweet_id = $data['tweet'];
        $this->text = $data['text'];
        $this->save();
    }

    /**
     * コメント削除
     *
     * @param  $id
     * 
     * @return void
     */
    public function commentDelete($id) : void
    {
        $this->where('id',$id)->delete();
    }

    /**
     * 一意のツイートのコメント取得
     *
     * @param  $tweetId
     * 
     * @return \Illuminate\Http\Response
     */
    public function comments($tweetId)
    {
        return $this->where('tweet_id', $tweetId)->get();
    }
}
