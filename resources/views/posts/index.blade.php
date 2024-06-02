@extends('../template')

@section('title', 'All Posts - TrendZine')

@extends('../partials/navbar')

@section('content')
    <div class="container">
        <div class="d-flex mb-3 border-bottom">
            <div class="me-5 pb-3 {{ Request::is('/') && !request('category') ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
                style="margin-bottom: -1px">
                <a href="/" class="nav-link">For you</a>
            </div>

            @foreach ($categories as $category)
                <div class="me-5  {{ request('category') == $category->slug ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
                    style="margin-bottom: -1px">
                    <a class="nav-link" aria-current="page"
                        href="/posts?category={{ $category->slug }}">{{ $category->name }}</a>
                </div>
            @endforeach
        </div>


        @if ($posts->count())
            <div class="col-10 mt-4 mx-auto">
                @foreach ($posts as $post)
                    <div class="card w-100 mb-3 border-0">
                        <div class="card-body d-flex gap-2">
                            <div class="col-10">
                                <p>
                                    <a class="text-decoration-none text-dark"
                                        href="/posts?author={{ $post->user->name }}">{{ $post->user->name }}</a>.
                                    <small class="text-secondary">{{ $post->created_at->diffForHumans() }}</small>
                                </p>
                                <h5 class="card-title">
                                    <a class="text-decoration-none text-dark"
                                        href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                                </h5>
                                <p class="card-text">
                                    <a class="text-decoration-none text-dark" href="/posts/{{ $post->slug }}">
                                        {{ Str::limit($post->content, 300, '...') }}
                                    </a>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex">
                                        @foreach ($post->categories as $category)
                                            <div class="px-3 rounded-pill me-2" style="background-color: #f6f6f6">
                                                {{ $category->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <svg class="me-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                        <path
                                            d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                                    </svg>
                                </div>
                            </div>
                            <div class="col-2 d-flex align-items-center">
                                @php
                                    // Mendapatkan kategori pertama dari post
                                    $category = $post->categories->first();
                                    // Mendapatkan URL gambar acak dari Unsplash dengan kategori yang sesuai
                                    $image_url = "https://source.unsplash.com/random/?{$category->name}";
                                @endphp
                                <img src="{{ $image_url }}" style="height: 70%; width: 100%" alt="">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No post found</p>
        @endif
    </div>
@endsection