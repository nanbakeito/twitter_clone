@extends('layouts.app')

@section('content')
    <tweet 
    :user= @json(auth()->user()->id)
    :name= '@json(auth()->user()->name)'
    :image= '@json(auth()->user()->profile_image)'
    >
    <tweet/>
@endsection
