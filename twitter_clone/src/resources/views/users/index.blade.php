@extends('layouts.app')

@section('content')
    <div><select-box :user= "@json(auth()->user()->id)"></select-box></div>
@endsection
