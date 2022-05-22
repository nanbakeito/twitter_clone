@extends('layouts.app')

@section('content')
<link href="{{ asset('/css/index.css') }}" rel="stylesheet">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach ($allUsers as $user)
                    <div class="card">
                        <div class="card-haeder p-3 w-100 d-flex">
                            <img src="{{ asset('storage/profile_image/' .$user->profile_image) }}" class="rounded-circle" width="50" height="50">
                            <div class="ml-2 d-flex flex-column">
                                <a href="{{ route('users.show', $user->id) }}" class="text-secondary">{{ $user->name }}</a>
                            </div>
                            @if (auth()->user()->isFollowed($user->id))
                                <div class="px-2">
                                    <span class="px-1 bg-secondary text-light">フォローされています</span>
                                </div>
                            @endif
                            <div class="d-flex justify-content-end flex-grow-1">
                                @if (auth()->user()->isFollowing($user->id))
                                    <form action="{{ route('unFollow', ['id' => $user->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-danger">フォロー解除</button>
                                    </form>   
                                @else
                                    <form action="{{ route('follow', ['id' => $user->id]) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-primary">フォローする</button>
                                    </form>                                                           
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="my-4 d-flex justify-content-center">
            {{ $allUsers->links() }}
        </div>
    </div>
@endsection 
