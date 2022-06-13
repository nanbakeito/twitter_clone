@extends('layouts.app')

@section('content')
    <select-box :user= "@json(auth()->user()->id)"></select-box>
@endsection
