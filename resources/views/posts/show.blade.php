@extends('template')

@section('title', 'Show Post - TrendZine')

@extends('../partials/navbar')

@section('content')
    <div class="row">
        <div class="col-8 mt-4 mx-auto">
            <h1>{{ $post->title }}</h1>
            <p>{{ $post->content }}</p>
        </div>
    </div>
@endsection
