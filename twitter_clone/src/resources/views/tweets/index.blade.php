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
                @if (isset($timelines))
                    @foreach ($timelines as $timeline)
                        <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
                        <div class="twitter__contents scroll">
                            <!-- 記事エリア -->
                            <div class="twitter__block">
                                <figure>
                                    <!-- tweet作成後に画像挿入（tweet機能未実装のため） -->
                                </figure>
                                <div class="twitter__block-text">
                                    <div class="name">{{ $user->name }}<span class="name_reply">@usa_tan</span></div>
                                    <div class="date">{{ $timeline->created_at->format('Y-m-d H:i') }}</div>
                                    <div class="text">
                                        {{ $timeline->text }}
                                    </div>
                                    <div class="in-pict">
                                        <img src="/images/sample.jpg">
                                    </div>
                                    <div class="twitter__icon">
                                        <span class="twitter-bubble">{{ count($timeline->comments) }}</span>
                                        <span class="twitter-loop">4</span>
                                        <span class="twitter-heart">{{ count($timeline->favorites) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="twitter__contents scroll">
                        ツイートはありません
                    </div>
                @endif    
        </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links() }}
    </div>
</div>
@endsection