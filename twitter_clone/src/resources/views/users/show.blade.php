@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/show.css') }}" rel="stylesheet">
<link href="{{ asset('/css/style.css') }}" rel="stylesheet">
<div class="container">
    <div class="row justify-content-center">
        <div class="profile__container">
            <figure>
                <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" />
            </figure>
            <div>
                {{$user->name}}
            </div>
            @if ($user->id === Auth::user()->id)
                <a href="{{ url('users/' .$user->id .'/edit') }}" class="btn btn-primary">プロフィールを編集する</a>
            @else
                @if (auth()->user()->isFollowing($user->id))
                    <form action="{{ route('unFollow', ['id' => $user->id]) }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger">フォロー解除</button>
                    </form>
                @else
                    <form action="{{ route('follow', ['id' => $user->id]) }}" method="POST">
                        {{ csrf_field() }}
                        <button type="submit" class="btn btn-primary">フォローする</button>
                    </form>
                @endif
                @if (auth()->user()->isFollowed($user->id))
                    <span class="mt-2 px-1 bg-secondary text-light">フォローされています</span>
                @endif
            @endif
            <div class="d-flex justify-content-end">
                <div class="p-2 d-flex flex-column align-items-center">
                    <p class="font-weight-bold">ツイート数</p>
                    <span>{{ $tweetCount }}</span>
                </div>
                <div class="p-2 d-flex flex-column align-items-center">
                    <p class="font-weight-bold">フォロー数</p>
                    <span>{{ $followCount }}</span>
                </div>
                <div class="p-2 d-flex flex-column align-items-center">
                    <p class="font-weight-bold">フォロワー数</p>
                    <span>{{ $followerCount }}</span>
                </div>
            </div>
        </div>
        <!-- ここからツイート -->
        @if ($tweetCount > 0)
            @foreach ($timelines as $timeline)
                <div class="twitter__container">
                    <!-- タイトル -->
                    <div class="twitter__title">
                        <span class="twitter-logo"></span>
                    </div>

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
                </div>
            @endforeach
        @else
            <div class="twitter__container">
                <div class="twitter__title">
                    <span class="twitter-logo"></span>
                </div>
                <div class="twitter__contents scroll">
                    ツイートはありません
                </div>
            </div>
        @endif           
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links() }}
    </div>
</div>
@endsection 
