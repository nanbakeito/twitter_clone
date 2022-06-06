@extends('layouts.app')

@section('content')
    <tweet :user= "@json(auth()->user()->id)"><tweet/>
@endsection
