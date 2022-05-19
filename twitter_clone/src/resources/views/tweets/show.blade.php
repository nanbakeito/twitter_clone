@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3 text-right">
            <a href="{{ url('users') }}">ユーザ一覧 <i class="fas fa-users" class="fa-fw"></i> </a>
        </div>
        <!-- ここからツイート -->
        <div class="twitter__container">
            <!-- タイトル -->
            <div class="twitter__title">
                <span class="twitter-logo"></span>
            </div>
            <div class="twitter__contents scroll">
                <!-- 記事エリア -->
                <div class="twitter__block">
                    <figure>
                        <!-- tweet作成後に画像挿入（tweet機能未実装のため） -->
                    </figure>
                    <div class="twitter__block-text">
                        <div class="name">{{ $user->name }}<span class="name_reply">@usa_tan</span></div>
                        <div class="date">{{ $tweet->created_at->format('Y-m-d H:i') }}</div>
                        <div class="text">
                            {{ $tweet->text }}
                        </div>
                        <div class="in-pict">
                            <img src="/images/sample.jpg">
                        </div>
                        <div class="twitter__icon">
                            <span class="twitter-bubble">{{ count($tweet->comments) }}</span>
                            <span class="twitter-loop">4</span>
                            <span class="twitter-heart">{{ count($tweet->favorites) }}</span>
                        </div>
                    </div>
                </div>
                <div class="comment__block">
                    <div class="col-md-8 mb-3">
                        <ul class="list-group">
                            @forelse ($comments as $comment)
                                <li class="list-group-item">
                                    <div class="py-3 w-100 d-flex">
                                        <img src="{{ asset('storage/profile_image/' .$comment->user->profile_image) }}" class="rounded-circle" width="50" height="50">
                                        <div class="ml-2 d-flex flex-column">
                                            <p class="mb-0">{{ $comment->user->name }}</p>
                                            <a href="{{ url('users/' .$comment->user->id) }}" class="text-secondary">{{ $comment->user->screen_name }}</a>
                                        </div>
                                        <div class="d-flex justify-content-end flex-grow-1">
                                            <p class="mb-0 text-secondary">{{ $comment->created_at->format('Y-m-d H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="py-3">
                                        {!! nl2br(e($comment->text)) !!}
                                    </div>
                                </li>
                            @empty
                                <li class="list-group-item">
                                    <p class="mb-0 text-secondary">コメントはまだありません。</p>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div> 
                
            </div>
        </div>
</div>
@endsection



