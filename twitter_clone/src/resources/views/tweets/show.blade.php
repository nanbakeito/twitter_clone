@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 mb-3">
            <div class="card">
                <div class="card-haeder p-3 w-100 d-flex">
                    @if(isset($tweet->user->profile_image))
                        <img src="{{ asset('storage/profile_image/' .$tweet->user->profile_image) }}" class="rounded-circle" width="50" height="50">
                    @else
                        <img src="{{ asset('storage/profile_image/noimage.png') }}" class="rounded-circle" width="50" height="50">
                    @endif
                    <div class="ml-2 d-flex flex-column">
                        <a href="{{ route('users.show', $tweet->user->id) }}" class="text-secondary">
                            <p class="mb-0">{{ $tweet->user->name }}</p>
                        </a>
                    </div>
                    <div class="d-flex justify-content-end flex-grow-1">
                        <p class="mb-0 text-secondary">{{ $tweet->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                @if (isset($tweet->image))
                    <img src="{{ asset('storage/image/' .$tweet->image) }}" >
                @else
                    <img src="{{ asset('storage/image/noimage.png') }}" >
                @endif
                <div class="card-body">
                    {!! nl2br(e($tweet->text)) !!}
                </div>
                <div class="card-footer py-1 d-flex justify-content-end bg-white">
                    @if ($tweet->user->id === Auth::user()->id)
                        <div class="dropdown mr-3 d-flex align-items-center">
                            <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-fw"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <form method="POST" action="{{ route('tweets.destroy', $tweet->id) }}" class="mb-0">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ route('tweets.edit', $tweet->id) }}" class="dropdown-item">編集</a>
                                    <button type="submit" class="dropdown-item del-btn">削除</button>
                                    <input name="id" type="hidden" value="{{ $tweet->id }}">
                                </form>
                            </div>
                        </div>
                    @endif
                    <!-- いいね -->
                    @if (!$tweet->isLikedBy(auth()->user()->id))
                        <span class="favorites">
                            <i class="fas fa-solid fa-thumbs-up favoriteToggle" data-tweet-id="{{ $tweet->id }}"></i>
                            <span class="favoriteCounter">{{$tweet->favoriteCount($tweet->id)}}</span>
                        </span>
                    @else
                        <span class="favorites">
                            <i class="fas fa-solid fa-thumbs-up favoriteToggle favorite" data-tweet-id="{{ $tweet->id }}"></i>
                            <span class="favoriteCounter">{{$tweet->favoriteCount($tweet->id)}}</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- コメントエリア -->
    <div class="row justify-content-center">
        <div class="col-md-8 mb-3">
            <ul class="list-group">
                {{-- comment.vue内のテンプレートを呼び出している（内容：コメント投稿フォーム，コメントリスト） --}}
                <comment 
                :user= "@json(auth()->user()->id)" 
                :tweet= "@json($tweet->id)"
                :tweet-user= "@json($tweet->user->id)" 
                ><comment>
            </ul>
        </div>
    </div>
</div>
@endsection
