@extends('template')

@section('title', 'Show Post')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Show Post</div>
                <div class="card-body">
                    <h1>{{ $post->title }}</h1>
                    <p>{{ $post->content }}</p>
                    <a href="{{ route('posts.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
