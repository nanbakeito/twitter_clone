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
                                <div class="d-flex justify-content-end flex-grow-1">
                                    @if (auth()->user()->isFollowing($user->id))
                                        <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                                        <button type="button" class="btn btn-danger" data-user-id="{{ $user->id }}">フォロー解除</button>
                                    @else
                                        <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                                        <button type="button" class="btn btn-primary" data-user-id="{{ $user->id }}">フォローする</button>                                                    
                                    @endif
                                </div>
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
                                <span class="followerCount">{{ $followerCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ここからツイート -->
        <tweet 
            :user= @json($user->id)
            :name= '@json($user->name)'
            :image= '@json($user->profile_image)'
            >
        <tweet/>
</div>
@endsection
