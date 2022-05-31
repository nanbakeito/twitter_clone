<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ValidationComment
{
    /**
     * ユーザーのバリデーション
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'tweet' =>['required', 'integer'],
            'text'     => ['required', 'string', 'max:140']
        ]);
        $validator->validate();
        
        return $next($request);
    }
}
