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
        @foreach ($posts as $post)
            <div class="card w-75 mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p>At Category
                        @foreach ($post->categories as $category)
                            {{ $category->name }}{{ !$loop->last ? ',' : '' }}
                        @endforeach
                    </p>
                    <p>By. {{ $post->user->name }}. <small class="text-secondary">
                            {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</small></p>
                    <p class="card-text">{{ Str::limit($post->content, 300, '...') }}</p>
                    <a href="/post/{{ $post->slug }}" class="btn btn-primary">Read more</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
