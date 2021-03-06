<div id="search">
    <label></label>
    <input type="text">
    <button type="button" @click="onClick">検索する</button>
</div>
<div>
    <follower-vue></follower-vue>
</div>


<div class="col-md-8">
    <dl class="col-md-8" v-for="user in users" :key="grade.subject" >
        <div class="card">
            <div class="card-haeder p-3 w-100 d-flex">
            @if(isset($user->profile_image))
                <img src="{{ asset('storage/profile_image/' user.profile_image) }}" class="rounded-circle" width="50" height="50">
            @endif
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
                            <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                            <button type="button" class="btn btn-danger" data-user-id="{{ $user->id }}">フォロー解除</button>
                    @else
                        <input name="userId" type="hidden" value="{{ auth()->user()->id }}">
                        <button type="button" class="btn btn-primary" data-user-id="{{ $user->id }}">フォローする</button>                                                    
                    @endif
                </div>
            </div>
        </div>
    </dl>
</div>


