@extends('../template')

@section('title', 'All Posts')

@extends('../partials/navbar')

@section('content')
    <div class="container">
        <div class="d-flex mb-3 border-bottom">
            <div class="me-5 pb-3 {{ Request::is('/') && !request('category') ? 'border-bottom border-black' : 'text-dark-emphasis' }}" style="margin-bottom: -1px">
                <a href="/" class="nav-link">For you</a>
            </div>

            @foreach ($categories as $category)
                <div class="me-5  {{ request('category') == $category->slug ? 'border-bottom border-black' : 'text-dark-emphasis' }}" style="margin-bottom: -1px">
                    <a class="nav-link" aria-current="page"
                        href="/posts?category={{ $category->slug }}">{{ $category->name }}</a>
                </div>
            @endforeach 
        </div>

        @if ($posts->count())
            @foreach ($posts as $post)
                <div class="card w-100 mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p>By. <a class="text-decoration-none" href="/posts?author={{ $post->user->name }}">{{ $post->user->name }}</a>. <small class="text-secondary">
                                {{ $post->created_at->diffForHumans() }}</small></p>
                        <p class="card-text">{{ Str::limit($post->content, 300, '...') }}</p>
                        <a href="/posts/{{ $post->slug }}" class="btn btn-primary">Read more</a>

                        <div class="d-flex mt-3">
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
