<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationTweet
{
    /**
     * ツイートのバリデーション
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tweetData = $request->all();
        if (!in_array('null', $tweetData['image'])) {
            $validator = Validator::make($tweetData, [
                'text' => ['required', 'string', 'max:140'],
                'image' => ['file', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ]);
        } else {
            $validator = Validator::make($tweetData, [
                'text' => ['required', 'string', 'max:140'],
            ]);        
        }
        $validator->validate();
        
        return $next($request);
    }
}
