@extends('layouts.app')

@section('content')
<div>
    <profile-box 
        :login-user= '@json(Auth::user()->id)'
        :login-user-name= '@json(Auth::user()->name)'
        :login-user-image= '@json(Auth::user()->profile_image)'
        :user= '@json($user->id)'
        :name= '@json($user->name)'
        :image= '@json($user->profile_image)'
        :is-following= '@json(auth()->user()->isFollowing($user->id))'
        :is-followed= '@json(auth()->user()->isFollowed($user->id))'
        :tweet-count= '@json($tweetCount)'
        :follow-count= '@json($followCount)'
        :follower-count= '@json($followerCount)'
        >
    <profile-box/>
</div>
<!-- ここからツイート -->
@endsection
