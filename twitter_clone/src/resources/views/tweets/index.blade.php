@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3 text-right">
            <a href="{{ route('users.index') }}">ユーザ一覧 <i class="fas fa-users" class="fa-fw"></i> </a>
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
                                    <!-- 画像挿入（tweet画像未実装のため） -->
                                </figure>
                                <div class="twitter__block-text">
                                    <div class="name">{{ $user->name }}<span class="name_reply">@usa_tan</span></div>
                                    <div class="date">{{ $timeline->created_at->format('Y-m-d H:i') }}</div>
                                    <div class="text">
                                        {{ $timeline->text }}
                                    </div>
                                    <div class="in-pict">
                                    </div>
                                    <div class="twitter__icon">
                                        @if ($timeline->user->id === Auth::user()->id)
                                            <div class="dropdown mr-3 d-flex align-items-center">
                                                <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v fa-fw"></i>
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    <form method="POST" action="{{ route('tweets.destroy', $timeline->id) }}" class="mb-0">
                                                        @csrf
                                                        <a href="{{ route('tweets.edit', $timeline->id) }}" class="dropdown-item">編集</a>
                                                        <button type="submit" class="dropdown-item del-btn">削除</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif                                       
                                        <span class="twitter-bubble">{{ count($timeline->comments) }}</span>
                                        <span class="twitter-loop">4</span>
                                        <span class="twitter-heart">{{ count($timeline->favorites) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div>
                        ツイートはありません
                    </div>
                @endif
        </div>
    @if (isset($timelines))
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links() }}
    </div>
    @endif
</div>
@endsection
