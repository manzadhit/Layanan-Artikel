@extends('../template')
@include('partials.logged_in_navbar', ['user' => Auth::user()])

@section('content')

    <div class="container mt-4">
        <div class="d-flex flex-column align-items-center gap-3">
            <h1 class="text-center m-0">{{ $category->name }}</h1>
            <div class="d-flex gap-2 align-items-center">
                <p class="m-0 text-secondary">{{ $category->followers->count() }} Followers</p>
                <span class="mx-1 align-middle">•</span>
                <p class="m-0 text-secondary">{{ $category->posts()->count() }} Posts</p>
            </div>
            <form action="{{ route('categories.follow', $category) }}" method="POST">
                @csrf
                <button type="submit"
                    class="btn {{ auth()->user()->followedCategories->contains($category) ? 'border-dark  text-dark' : 'btn-dark' }}">
                    {{ auth()->user()->followedCategories->contains($category) ? 'Unfollow' : 'Follow' }}
                </button>
            </form>
        </div>
        @if ($posts->count() > 0)
            <div class="col-10 mt-4 mx-auto">
                @foreach ($posts as $post)
                    <div class="card w-100 mb-3 border-0">
                        <div class="card-body d-flex gap-2">
                            <div class="col-9 me-4">
                                <div class="d-flex gap-2 align-items-center mb-2">
                                    <a class="d-flex text-decoration-none text-dark gap-2"
                                        href="{{ route('profile', ['username' => $post->user->username]) }}"
                                        style="font-size: 0.9rem">
                                        @if ($post->user->profile_image)
                                            <img src="{{ asset('profile_images/' . $post->user->profile_image) }}"
                                                alt="Profile Picture" class="rounded-circle"
                                                style="width: 25px; height: 25px; cursor: pointer"
                                                data-bs-toggle="dropdown">
                                        @else
                                            <div id="profile-color"
                                                class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                                style="height: 25px; width: 25px; background-color: {{ $post->user->profile_color }}">
                                                <span style="font-size: 0.9rem; pointer-events: none;"
                                                    class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $post->user->name[0] }}
                                                </span>
                                            </div>
                                        @endif
                                        <p class="underline-hover m-0" style="font-size: 1rem">
                                            {{ $post->user->name }}
                                        </p>
                                    </a>
                                    &#46;
                                    <p class="m-0">
                                        <small class="text-secondary m-0">{{ $post->created_at->diffForHumans() }}</small>
                                    </p>
                                </div>
                                <h5 class="card-title">
                                    <a class="text-decoration-none text-dark"
                                        href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                                </h5>
                                <p class="card-text">
                                    <a class="text-decoration-none text-dark" href="/posts/{!! $post->slug !!}">
                                        {!! Str::limit($getFirstTagRegex($post->content), 300) !!}
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
                                    <div class=" d-flex gap-2 me-2">
                                        {{-- saved form --}}
                                        @if (auth()->check())
                                            <form class="m-0 saveForm" data-post-id="{{ $post->id }}">
                                                @csrf
                                                <input type="hidden" name="post_id" value="{{ $post->id }}">
                                                <button type="button" class="save-button"
                                                    data-saved="{{ $post->isSaved ? 'true' : 'false' }}"
                                                    style="background: none; border: none; cursor: pointer;">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                        class="{{ $post->isSaved ? 'ajw' : 'lm' }}">
                                                        <path
                                                            d="{{ $post->isSaved
                                                                ? 'M7.5 3.75a2 2 0 0 0-2 2v14a.5.5 0 0 0 .8.4l5.7-4.4 5.7 4.4a.5.5 0 0 0 .8-.4v-14a2 2 0 0 0-2-2h-9z'
                                                                : 'M17.5 1.25a.5.5 0 0 1 1 0v2.5H21a.5.5 0 0 1 0 1h-2.5v2.5a.5.5 0 0 1-1 0v-2.5H15a.5.5 0 0 1 0-1h2.5v-2.5zm-11 4.5a1 1 0 0 1 1-1H11a.5.5 0 0 0 0-1H7.5a2 2 0 0 0-2 2v14a.5.5 0 0 0 .8.4l5.7-4.4 5.7 4.4a.5.5 0 0 0 .8-.4v-8.5a.5.5 0 0 0-1 0v7.48l-5.2-4a.5.5 0 0 0-.6 0l-5.2 4V5.75z' }}"
                                                            fill="#000"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                        {{-- other form --}}
                                        @if (auth()->check())
                                            <div class="dropdown">
                                                <svg id="report-button" data-bs-toggle="dropdown" width="24"
                                                    height="24" style="cursor: pointer" viewBox="0 0 24 24"
                                                    fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M4.39 12c0 .55.2 1.02.59 1.41.39.4.86.59 1.4.59.56 0 1.03-.2 1.42-.59.4-.39.59-.86.59-1.41 0-.55-.2-1.02-.6-1.41A1.93 1.93 0 0 0 6.4 10c-.55 0-1.02.2-1.41.59-.4.39-.6.86-.6 1.41zM10 12c0 .55.2 1.02.58 1.41.4.4.87.59 1.42.59.54 0 1.02-.2 1.4-.59.4-.39.6-.86.6-1.41 0-.55-.2-1.02-.6-1.41a1.93 1.93 0 0 0-1.4-.59c-.55 0-1.04.2-1.42.59-.4.39-.58.86-.58 1.41zm5.6 0c0 .55.2 1.02.57 1.41.4.4.88.59 1.43.59.57 0 1.04-.2 1.43-.59.39-.39.57-.86.57-1.41 0-.55-.2-1.02-.57-1.41A1.93 1.93 0 0 0 17.6 10c-.55 0-1.04.2-1.43.59-.38.39-.57.86-.57 1.41z"
                                                        fill="currentColor"></path>
                                                </svg>
                                                <ul class="dropdown-menu p-2" id="report-dropdown"
                                                    style="min-width: 150px; font-size: .95rem">
                                                    @if (auth()->check() && auth()->id() == $post->user->id)
                                                        <li>
                                                            <a class="m-0 text-decoration-none" id="profile-form"
                                                                href="{{ route('posts.edit', ['slug' => $post->slug]) }}">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="dropdown-item d-flex align-items-center text-center">
                                                                    Edit post
                                                                </button>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('posts.destroy', $post->slug) }}"
                                                                method="POST" class="mb-0">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item d-flex align-items-center text-danger"
                                                                    onclick="return confirm('Anda yakin ingin menghapus post ini?')">Delete
                                                                    post</button>
                                                            </form>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <form class="m-0 d-flex align-items-center text-center"
                                                                id="follow-form">
                                                                @csrf
                                                                <input type="hidden" name="followed_user_id"
                                                                    value="{{ $post->user ? $post->user->id : '' }}">
                                                                <button type="button" id="followBtn"
                                                                    class="follow-btn dropdown-item d-flex align-items-center text-center text"
                                                                    style="cursor: pointer;"
                                                                    data-following="{{ auth()->user()->isFollowing($post->user)? 'true': 'false' }}"
                                                                    data-user-id="{{ $post->user->id }}">
                                                                    {{ auth()->user()->isFollowing($post->user)? 'Unfollow author': 'Follow author' }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <button type="button"
                                                                class="dropdown-item d-flex align-items-center text-center text-danger"
                                                                id="reportPostBtn">
                                                                Report post
                                                            </button>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-3 d-flex align-items-center">
                                @if ($post->postImages->isNotEmpty())
                                    {{-- Ambil gambar pertama dari koleksi gambar --}}
                                    @php
                                        $image = $post->postImages->first(); // Ambil objek gambar pertama
                                    @endphp
                                    <img src="{{ asset('images/' . $image->image_name) }}"
                                        style="height: 150px; width: 90%" alt="{{ $post->title }}">
                                @else
                                    {{-- Placeholder jika tidak ada gambar --}}
                                    <img src="https://picsum.photos/seed/picsum/200/300" style="height: 150px; width: 90%"
                                        alt="Placeholder Image">
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center">No posts found in this category.</p>
        @endif
    </div>
@endsection
