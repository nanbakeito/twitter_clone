<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Tweet;

class FavoritesController extends Controller
{
    /**
     * いいね
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function favorite(Request $request)
    {
        $userId = Auth()->user()->id; 
        $tweetId = $request->tweetId; 
        $alreadyFavorite = Favorite::where('user_id', $userId)->where('tweet_id', $tweetId)->first();
    
        // ユーザーがいいねを押していない場合
        if (!$alreadyFavorite) { 
            $favorite = new Favorite; 
            $favorite->tweet_id = $tweetId; 
            $favorite->user_id = $userId;
            $favorite->save();

        } else { 
            Favorite::where('tweet_id', $tweetId)->where('user_id', $userId)->delete();
        }
        
        $tweetFavoritesCount = Tweet::withCount('favorites')->findOrFail($tweetId)->favoriteCount($tweetId);
        $param = [
            'tweetFavoritesCount' => $tweetFavoritesCount,
        ];
        return response()->json($param); 
    }
}
