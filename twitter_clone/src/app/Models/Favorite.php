<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    public $timestamps = false;

    /**
     * usersテーブルとのリレーションを定義する
     */
    public function user()
    {   
        return $this->belongsTo(User::class);
    }

    /**
     * tweetsテーブルとのリレーションを定義する
     */
    public function review()
    {   
        return $this->belongsTo(Tweet::class);
    }
}
