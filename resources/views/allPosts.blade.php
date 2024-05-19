@extends('main')

@section('container')
    <div class="container">
        <h3>Halaman Post</h3>
        <div class="d-flex mb-3 border-bottom">
            <div class="me-5 p-2 border-bottom">
                <a href="/posts">For you</a>
            </div>
            @foreach ($categories as $category)
                <div class="me-5 p-2 border-bottom">
                    <a href="/category/{{ $category->slug }}">{{ $category->name }}</a>
                </div>
            @endforeach
        </div>
        @if ($posts->count())
            @foreach ($posts as $post)
                <div class="card w-100 mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p>By. {{ $post->user->name }}. <small class="text-secondary">
                                {{ $post->created_at->diffForHumans() }}</small></p>
                        <p class="card-text">{{ Str::limit($post->content, 300, '...') }}</p>
                        <a href="/post/{{ $post->slug }}" class="btn btn-primary">Read more</a>

                        <div class="d-flex mt-2">
                            @foreach ($post->categories as $category)
                                <div class="bg-body-secondary px-3 rounded-pill py-1 me-3">
                                    {{ $category->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach   
        @else
            <p class="text-center">No post found</p>
        @endif
    </div>
@endsection
