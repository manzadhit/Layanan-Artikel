@extends('../template')
@include('partials.logged_in_navbar', ['user' => Auth()->user()])

@section('styles')
    <style>
        .underline-hover {
            text-decoration: none;
            /* Menghilangkan garis bawah */
            transition: text-decoration 0.3s ease;
            /* Efek transisi */
        }

        .underline-hover:hover {
            text-decoration: underline;
            /* Menambah garis bawah saat dihover */
        }
    </style>
@endsection

@section('content')
    <h1 class="text-center mt-5"><span class="text-secondary">Result for</span> {{ request('q') }}</h1>
    <div class="d-flex gap-5 mb-5 mt-3 pt-4 border-bottom col-4 mx-auto justify-content-center">
        <div class="pb-3 {{ $type == 'posts' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
            style="margin-bottom: -1px;">
            <a href="{{ route('search.type', ['type' => 'posts', 'q' => request('q')]) }}" class="nav-link">Posts</a>
        </div>
        <div class=" pb-3 {{ $type == 'authors' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
            style="margin-bottom: -1px;">
            <a href="{{ route('search.type', ['type' => 'authors', 'q' => request('q')]) }}" class="nav-link">Authors</a>
        </div>
        <div class=" pb-3 {{ $type == 'categories' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
            style="margin-bottom: -1px;">
            <a href="{{ route('search.type', ['type' => 'categories', 'q' => request('q')]) }}"
                class="nav-link">Categories</a>
        </div>
    </div>
    @if (isset($results['posts']))
        @if ($results['posts']->isEmpty())
            <p class="text-center">No posts found</p>
        @else
            <div class="col-10 mt-4 mx-auto">
                @foreach ($results['posts'] as $post)
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
            {{ $results['posts']->appends(request()->query())->links('pagination::bootstrap-5') }}
        @endif
    @elseif(isset($results['authors']))
        @if ($results['authors']->isEmpty())
            <p class="text-center">No authors found</p>
        @else
            <div class="d-flex flex-column col-8 mx-auto">
                @foreach ($results['authors'] as $author)
                    @if ($author->id !== auth()->id())
                        <div class="mb-3">
                            <a class="d-flex text-decoration-none text-dark gap-3"
                                href="{{ route('profile', ['username' => $author->username]) }}"
                                style="font-size: 0.9rem">
                                @if ($author->profile_image)
                                    <img src="{{ asset('profile_images/' . $author->profile_image) }}"
                                        alt="Profile Picture" class="rounded-circle"
                                        style="width: 45px; height: 45px; cursor: pointer" data-bs-toggle="dropdown">
                                @else
                                    <div id="profile-color"
                                        class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                        style="height: 45px; width: 45px; background-color: {{ $author->profile_color }}">
                                        <span style="font-size: 2rem; pointer-events: none;"
                                            class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $author->name[0] }}
                                        </span>
                                    </div>
                                @endif
                                <div class="d-flex flex-column">
                                    <p class="underline-hover m-0 fs-5 my-auto">
                                        {{ $author->name }}
                                    </p>
                                    <div class="d-flex">
                                        <p class="m-0 text-secondary">{{ $author->followers()->count() }} Followers</p>
                                        <span class="mx-1 align-middle">•</span>
                                        <p class="m-0 text-secondary">{{ $author->posts()->count() }} Posts</p>
                                    </div>
                                </div>
                                <form class="m-0 my-auto ms-auto" id="follow-form">
                                    @csrf
                                    <input type="hidden" name="followed_user_id"
                                        value="{{ $author ? $author->id : '' }}">
                                    <div class="p-0 m-0">
                                        @if (auth()->id() != $author->id)
                                            <button type="button" id="followBtn"
                                                class="follow-btn rounded-pill {{ auth()->user()->isFollowing($author) ? 'border-success text-success' : 'btn-success' }} btn"
                                                style="cursor: pointer;"
                                                data-following="{{ auth()->user()->isFollowing($author) ? 'true' : 'false' }}"
                                                data-user-id="{{ $author->id }}">
                                                {{ auth()->user()->isFollowing($author) ? 'Following' : 'Follow' }}
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </a>
                        </div>
                    @endif
                @endforeach
            </div>
            {{ $results['authors']->appends(request()->query())->links() }}
        @endif
    @elseif(isset($results['categories']))
        @if ($results['categories']->isEmpty())
            <p class="text-center">No categories found</p>
        @else
            <div class="col-8 mx-auto">
                @foreach ($results['categories'] as $category)
                    <div class="mb-3">
                        <a class="d-flex text-decoration-none text-dark gap-3 align-items-center"
                            href="{{ route('category.posts', ['slug' => $category->slug]) }}" style="font-size: 0.9rem">
                            <div id="profile-color"
                                class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                style="height: 45px; width: 45px; background-color: #F2F2F2">
                                <span style="font-size: 2rem; pointer-events: none;"
                                    class="m-0 text-center position-absolute top-50 start-50 translate-middle">
                                    <svg style="color: black" width="16" height="16" viewBox="0 0 16 16"
                                        fill="" class="rx">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M3 14V2h10v12H3zM2.75 1a.75.75 0 0 0-.75.75v12.5c0 .41.34.75.75.75h10.5c.41 0 .75-.34.75-.75V1.75a.75.75 0 0 0-.75-.75H2.75zM5 10.5a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zM4.5 9c0-.28.22-.5.5-.5h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zm1.25-2.5h4.5c.14 0 .25-.11.25-.25v-1.5a.25.25 0 0 0-.25-.25h-4.5a.25.25 0 0 0-.25.25v1.5c0 .14.11.25.25.25z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="underline-hover m-0 fs-5 my-auto">
                                    {{ $category->name }}
                                </p>
                                <div class="d-flex gap-2 align-items-center">
                                    <p class="m-0 text-secondary">{{ $category->followers->count() }} Followers</p>
                                    <span class="mx-1 align-middle">•</span>
                                    <p class="m-0 text-secondary">{{ $category->posts()->count() }} Posts</p>
                                </div>
                            </div>
                            <form class="m-0 ms-auto" action="{{ route('categories.follow', $category) }}"
                                method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn {{ auth()->user()->followedCategories->contains($category) ? 'border-success  text-success' : 'btn-success' }}">
                                    {{ auth()->user()->followedCategories->contains($category) ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                        </a>
                    </div>
                @endforeach
            </div>
            {{ $results['categories']->appends(request()->query())->links() }}
        @endif
    @else
        <p>No results found</p>
    @endif
@endsection

@push('scripts')
        <script>
        $(document).ready(function() {
            // Handler untuk tombol follow
            $(document).on('click', '.follow-btn', function() {
                    var btn = $(this);
                    var isFollowing = btn.data('following') === 'true';
                    var userId = btn.data('user-id');

                    @auth
                    var authUserId = {{ auth()->id() }};
                    $.ajax({
                        url: "{{ secure_url(route('toggle.follow', ['userId' => ':userId'])) }}"
                            .replace(':userId', authUserId),
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            followed_user_id: userId
                        },
                        success: function(response) {
                            if (response.isFollowing) {
                                btn.text('Following').data('following', 'true');
                            } else {
                                btn.text('Follow').data('following', 'false');
                            }

                            setTimeout(function() {
                                location.reload();
                            }, 200); // Delay 300ms
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred. Please try again.');
                        }
                    });
                @else
                    // Jika pengguna belum login, arahkan ke halaman login
                    window.location.href = "{{ route('login') }}";
                @endauth
            });

        // Handler untuk tombol "Login to Follow"
        $(document).on('click', '#loginToFollowBtn', function() {
            window.location.href = "{{ route('login') }}";
        });
        });
    </script>
@endpush
