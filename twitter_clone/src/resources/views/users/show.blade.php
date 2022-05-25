@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="d-inline-flex">
                    <div class="p-3 d-flex flex-column">
                        @if(isset($user->profile_image))
                            <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="100" height="100">
                        @endif
                        <div class="mt-3 d-flex flex-column">
                            <h4 class="mb-0 font-weight-bold">{{ $user->name }}</h4>
                        </div>
                    </div>
                    <div class="p-3 d-flex flex-column justify-content-between">
                        <div class="d-flex">
                            <div>
                                @if ($user->id === Auth::user()->id)
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">プロフィールを編集する</a>
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
                            </div>
                        </div>
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
                </div>
            </div>
        </div>
        <!-- ここからツイート -->
        @if ($tweetCount > 0)
            @foreach ($timelines as $timeline)
                <div class="col-md-8 mb-3">
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                        @if(isset($timeline->user->profile_image))
                            <img src="{{ asset('storage/profile_image/' .$timeline->user->profile_image) }}" class="rounded-circle" width="50" height="50">
                        @endif
                            <div class="ml-2 d-flex flex-column">
                                <a href="{{ route('users.show', $timeline->user->id) }}" class="text-secondary">
                                    <p class="mb-0">{{ $timeline->user->name }}</p>
                                </a>
                            </div>
                            <div class="d-flex justify-content-end flex-grow-1">
                                <p class="mb-0 text-secondary">{{ $timeline->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                        @if (isset($timeline->image))
                            <img src="{{ asset('storage/image/' .$timeline->image) }}" width="500" height="500">
                        @endif
                        <div class="card-body">
                            {!! nl2br(e($timeline->text)) !!}
                        </div>
                        <div class="card-footer py-1 d-flex justify-content-end bg-white">
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
                            <!-- コメントアイコン -->
                            <div class="mr-3 d-flex align-items-center">
                                <a href="{{ route('tweets.show', $timeline->id) }}"><i class="far fa-comment fa-fw"></i></a>
                                <p class="mb-0 text-secondary">{{ count($timeline->comments) }}</p>
                            </div>
                            <!-- ここからいいね -->
                            @if (!$timeline->isLikedBy(auth()->user()))
                                <span class="favorites">
                                    <i class="fas fa-solid fa-thumbs-up favoriteToggle" data-tweet-id="{{ $timeline->id }}"></i>
                                    <span class="favoriteCounter">{{$timeline->favoriteCount($timeline->id)}}</span>
                                </span>
                            @else
                                <span class="favorites">
                                    <i class="fas fa-solid fa-thumbs-up favoriteToggle favorite" data-tweet-id="{{ $timeline->id }}"></i>
                                    <span class="favoriteCounter">{{$timeline->favoriteCount($timeline->id)}}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="my-4 d-flex justify-content-center">
        {{ $timelines->links() }}
    </div>
</div>
@endsection
