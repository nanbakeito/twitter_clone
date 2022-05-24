@extends('layouts.app')

@section('content')
<button id="test_jquery">ぽちっとな</button>
<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3 text-right">
            <a href="{{ route('users.index') }}">ユーザ一覧 <i class="fas fa-users" class="fa-fw"></i> </a>
        </div>
        <!-- ここからツイート -->
        @if (isset($timelines))
            <div class="twitter__container">
                <!-- タイトル -->
                <div class="twitter__title">
                    <span class="twitter-logo"></span>
                </div>
                @foreach ($timelines as $timeline)
                    <!-- ▼タイムラインエリア scrollを外すと高さ固定解除 -->
                    <div class="twitter__contents scroll">
                        <!-- 記事エリア -->
                        <div class="twitter__block">
                            <figure>
                                <img src="{{ asset('storage/profile_image/' .$timeline->user->profile_image) }}" class="rounded-circle" width="50" height="50">
                            </figure>
                            <div class="twitter__block-text">
                                <a href="{{ route('users.show', $timeline->user->id) }}" class="text-secondary">{{ $timeline->user->name }}</a>
                                <div class="date">{{ $timeline->created_at->format('Y-m-d H:i') }}</div>
                                <div class="text">
                                    {{ $timeline->text }}
                                </div>
                                <div class="in-pict">
                                    <img src="{{ asset('storage/image/' .$timeline->image) }}" >
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
                                                @method('DELETE')
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
            </div>
            <div class="my-4 d-flex justify-content-center">
                {{ $timelines->links() }}
            </div>
        @else
            <div>
                ツイートはありません
            </div>
        @endif
    </div>
</div>
@endsection
