@extends('main')

@section('container')
    <div class="card w-75 mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p>By. {{ $post->user->name }}. <span class="fw-light">
                    {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</span></p>
            <p class="card-text">{{ $post->content }}</p>
        </div>
    </div>
@endsection
