@extends('../template')

@section('title', 'Profile - TrendZine')

@include('partials.logged_in_navbar', ['user' => $user_login])

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
    <div class="d-flex h-100 w-100 row">
        @if ($menu == 'notifications')
            <div class="col-8 border-end d-flex flex-column pe-5 pt-5">
                <p class="fw-light"><a href="{{ route('profile', ['username' => $user->username, 'menu' => 'all']) }}"
                        class="underline-hover text-dark" style="cursor: pointer">{{ $user->name }}</a> > notifications
                </p>
                @if ($notifications->isNotEmpty())
                    <h1 class="fw-semibold mb-5 mt-3">{{ $notifications->count() }} Notifications</h1>
                    @foreach ($notifications as $index => $notification)
                        <div class="d-flex gap-3 mb-4">
                            <!-- Link gambar profil -->
                            <a href="{{ route('profile', ['username' => $userResponded[$index]->username]) }}"
                                class="flex-shrink-0">
                                @if ($userResponded[$index]->profile_image)
                                    <img src="{{ asset('profile_images/' . $userResponded[$index]->profile_image) }}"
                                        alt="Profile Picture" class="rounded-circle"
                                        style="width: 45px; height: 45px; cursor: pointer">
                                @else
                                    <div id="profile-color"
                                        class="text-white border-0 btn-secondary rounded-circle position-relative"
                                        style="height: 45px; width: 45px; background-color: {{ $userResponded[$index]->profile_color }}">
                                        <span style="font-size: 2rem; pointer-events: none;"
                                            class="m-0 text-center position-absolute top-50 start-50 translate-middle">
                                            {{ $userResponded[$index]->name[0] }}
                                        </span>
                                    </div>
                                @endif
                            </a>

                            <!-- Konten notifikasi -->
                            <div class="flex-grow-1">
                                <a class="text-decoration-none text-dark"
                                    href="{{ route('profile', ['username' => $userResponded[$index]->username]) }}">
                                    <p class="m-0 text-grey">
                                        @if ($notification->type === 'App\Notifications\UserFollowed')
                                            <span
                                                style="font-size: 1.2rem">{{ $notification->data['follower_name'] }}</span>
                                            <span class="text-secondary" style="font-size: 1rem">started following
                                                you.</span>
                                        @elseif ($notification->type === 'App\Notifications\PostLiked')
                                            <span style="font-size: 1.2rem">{{ $notification->data['liker_name'] }}</span>
                                            <a class="text-secondary underline-hover" style="font-size: 1rem"
                                                href="{{ route('posts.show', ['slug' => $notification->data['post_slug']]) }}"
                                                class="text-decoration-none text-dark">
                                                liked your post: {!! Str::limit($notification->data['post_title'], 35) !!}
                                            </a>
                                        @elseif ($notification->type === 'App\Notifications\PostCommented')
                                            <span
                                                style="font-size: 1.2rem">{{ $notification->data['commenter_name'] }}</span>
                                            <a class="text-secondary underline-hover" style="font-size: 1rem"
                                                href="{{ route('posts.show', ['slug' => $notification->data['post_slug']]) }}"
                                                class="text-decoration-none text-dark">
                                                commented on your post: {!! Str::limit($notification->data['post_title'], 28) !!}
                                            </a>
                                        @elseif ($notification->type === 'App\Notifications\PostSaved')
                                            <span style="font-size: 1.2rem">{{ $notification->data['saver_name'] }}</span>
                                            <a class="text-secondary underline-hover" style="font-size: 1rem"
                                                href="{{ route('posts.show', ['slug' => $notification->data['post_slug']]) }}"
                                                class="text-decoration-none text-dark">
                                                saved your post: {!! Str::limit($notification->data['post_title'], 35) !!}
                                            </a>
                                        @elseif ($notification->type === 'App\Notifications\NewUserNotification')
                                            <span style="font-size: 1.2rem">A new user has
                                                registered.</span>
                                            <span class="text-secondary underline-hover" style="font-size: 1rem">{{ $userResponded[$index]->name }}</span>
                                        @endif
                                    </p>
                                </a>

                                <small class="text-secondary m-0">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p class="text-center fs-4 mt-5">No notifications found</p>
                @endif

            </div>
        @elseif ($menu == 'followers')
            <div class="col-8 border-end d-flex flex-column pe-5 pt-5">
                <p class="fw-light"><a href="{{ route('profile', ['username' => $user->username, 'menu' => 'all']) }}"
                        class="underline-hover text-dark" style="cursor: pointer">{{ $user->name }}</a> > followers</p>
                <h1 class="fw-semibold mb-4 mt-2">{{ $user->followers->count() }} Followers</h1>
                @foreach ($user->followers as $follower)
                    <div class="mb-3">
                        <a class="d-flex text-decoration-none text-dark gap-3"
                            href="{{ route('profile', ['username' => $follower->username]) }}" style="font-size: 0.9rem">
                            @if ($follower->profile_image)
                                <img src="{{ asset('profile_images/' . $follower->profile_image) }}" alt="Profile Picture"
                                    class="rounded-circle" style="width: 45px; height: 45px; cursor: pointer"
                                    data-bs-toggle="dropdown">
                            @else
                                <div id="profile-color"
                                    class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                    style="height: 45px; width: 45px; background-color: {{ $follower->profile_color }}">
                                    <span style="font-size: 2rem; pointer-events: none;"
                                        class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $follower->name[0] }}
                                    </span>
                                </div>
                            @endif
                            <div class="d-flex flex-column ">
                                <p class="underline-hover m-0 fs-5 my-auto">
                                    {{ $follower->name }}
                                </p>
                                <div class="d-flex">
                                    <p class="m-0 text-secondary">{{ $follower->followers()->count() }} Followers</p>
                                    <span class="mx-1 align-middle">•</span>
                                    <p class="m-0 text-secondary">{{ $follower->posts()->count() }} Posts</p>
                                </div>
                            </div>
                            <form class="m-0 my-auto ms-auto" id="follow-form">
                                @csrf
                                <input type="hidden" name="followed_user_id" value="{{ $follower ? $follower->id : '' }}">
                                <div class="p-0 m-0">
                                    @if (auth()->id() != $follower->id)
                                        <button type="button" id="followBtn"
                                            class="follow-btn rounded-pill {{ auth()->user()->isFollowing($follower) ? 'border-success text-success' : 'btn-success' }} btn"
                                            style="cursor: pointer;"
                                            data-following="{{ auth()->user()->isFollowing($follower) ? 'true' : 'false' }}"
                                            data-user-id="{{ $follower->id }}">
                                            {{ auth()->user()->isFollowing($follower) ? 'Following' : 'Follow' }}
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </a>
                    </div>
                @endforeach
            </div>
        @elseif ($menu == 'following')
            <div class="col-8 border-end d-flex flex-column pe-5 pt-5">
                <p class="fw-light"><a href="{{ route('profile', ['username' => $user->username, 'menu' => 'all']) }}"
                        class="underline-hover text-dark" style="cursor: pointer">{{ $user->name }}</a> > following</p>
                <h1 class="fw-semibold mb-4 mt-2">{{ $user->following->count() }} Following</h1>
                @foreach ($user->following as $follow)
                    <div class="mb-3">
                        <a class="d-flex text-decoration-none text-dark gap-3"
                            href="{{ route('profile', ['username' => $follow->username]) }}" style="font-size: 0.9rem">
                            @if ($follow->profile_image)
                                <img src="{{ asset('profile_images/' . $follow->profile_image) }}" alt="Profile Picture"
                                    class="rounded-circle" style="width: 45px; height: 45px; cursor: pointer"
                                    data-bs-toggle="dropdown">
                            @else
                                <div id="profile-color"
                                    class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                    style="height: 45px; width: 45px; background-color: {{ $follow->profile_color }}">
                                    <span style="font-size: 2rem; pointer-events: none;"
                                        class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $follow->name[0] }}
                                    </span>
                                </div>
                            @endif
                            <div class="d-flex flex-column ">
                                <p class="underline-hover m-0 fs-5 my-auto">
                                    {{ $follow->name }}
                                </p>
                                <div class="d-flex">
                                    <p class="m-0 text-secondary">{{ $follow->followers()->count() }} Followers</p>
                                    <span class="mx-1 align-middle">•</span>
                                    <p class="m-0 text-secondary">{{ $follow->posts()->count() }} Posts</p>
                                </div>
                            </div>
                            <form class="m-0 my-auto ms-auto" id="follow-form">
                                @csrf
                                <input type="hidden" name="followed_user_id" value="{{ $follow ? $follow->id : '' }}">
                                <div class="p-0 m-0">
                                    @if (auth()->id() != $follow->id)
                                        <button type="button" id="followBtn"
                                            class="follow-btn rounded-pill {{ auth()->user()->isFollowing($follow) ? 'border-success text-success' : 'btn-success' }} btn"
                                            style="cursor: pointer;"
                                            data-following="{{ auth()->user()->isFollowing($follow) ? 'true' : 'false' }}"
                                            data-user-id="{{ $follow->id }}">
                                            {{ auth()->user()->isFollowing($follow) ? 'Following' : 'Follow' }}
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="col-8 border-end d-flex flex-column pe-5 pt-5">
                <div class="d-flex justify-content-between align-items-center ">
                    <h1 class="m-0">{{ $user->name }}</h1>
                </div>
                {{-- menu --}}
                <div class="mt-4">
                    <div class="d-flex mb-4 border-bottom">
                        <div class="me-5 pb-3 {{ $menu === 'created' || $menu === null ? 'border-bottom border-black' : '' }}"
                            style="margin-bottom: -1px; font-size: .95rem">
                            <a href="{{ route('profile', ['username' => $user->username, 'menu' => 'created']) }}"
                                class="nav-link">Created Posts</a>
                        </div>
                        <div class="me-5 pb-3 {{ $menu === 'saved' ? 'border-bottom border-black' : '' }}"
                            style="margin-bottom: -1px">
                            <a href="{{ route('profile', ['username' => $user->username, 'menu' => 'saved']) }}"
                                class="nav-link">Saved Posts</a>
                        </div>
                        <div class="me-5 pb-3 {{ $menu === 'liked' ? 'border-bottom border-black' : '' }}"
                            style="margin-bottom: -1px">
                            <a href="{{ route('profile', ['username' => $user->username, 'menu' => 'liked']) }}"
                                class="nav-link">Liked Posts</a>
                        </div>
                    </div>
                </div>
                {{-- all post --}}
                @if ($posts->count())
                    <div class="w-100">
                        @foreach ($posts as $post)
                            <div class="card w-100 mb-5 border-0">
                                <div class="card-body d-flex gap-2 p-0">
                                    <div class="col-9 me-3">
                                        <div class="d-flex gap-2 align-items-center mb-2">
                                            <a class="d-flex text-decoration-none text-dark gap-2"
                                                href="{{ route('profile', ['username' => $post->user->username]) }}"
                                                style="font-size: 0.9rem">
                                                @if ($post->user->profile_image)
                                                    <img src="{{ asset('profile_images/' . $post->user->profile_image) }}"
                                                        alt="Profile Picture" class="rounded-circle"
                                                        style="width: 25px; height: 25px; object-fit: cover; cursor: pointer;"
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
                                                <p class="underline-hover m-0 " style="font-size: 1rem">
                                                    {{ $post->user->name }}
                                                </p>
                                            </a>
                                            &#46;
                                            <p class="m-0">
                                                <small
                                                    class="text-secondary m-0">{{ $post->created_at->diffForHumans() }}</small>
                                            </p>
                                        </div>
                                        <h5 class="card-title">
                                            <a class="text-decoration-none text-dark"
                                                href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                                        </h5>
                                        <p class="card-text">
                                            <a class="text-decoration-none text-dark"
                                                href="/posts/{!! $post->slug !!}">
                                                {{ Str::limit($getFirstTagRegex($post->content), 150) }}
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
                                                        <input type="hidden" name="post_id"
                                                            value="{{ $post->id }}">
                                                        <button type="button" class="save-button"
                                                            data-saved="{{ $post->isSaved ? 'true' : 'false' }}"
                                                            style="background: none; border: none; cursor: pointer;">
                                                            <!-- SVG code here -->
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none"
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
                                                                    <form
                                                                        action="{{ route('posts.destroy', $post->slug) }}"
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
                                                style="height: 150px; width: 100%" alt="{{ $post->title }}">
                                        @else
                                            {{-- Placeholder jika tidak ada gambar --}}
                                            <img src="https://picsum.photos/seed/picsum/200/300"
                                                style="height: 150px; width: 100%" alt="Placeholder Image">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center">No post found</p>
                @endif
            </div>
        @endif
        {{-- profile info --}}
        <div class="col-4 pt-5 ps-5">
            {{-- profile name --}}
            <div class="d-flex align-items-center justify-content-between">
                @if ($user->profile_image)
                    <img src="{{ asset('profile_images/' . $user->profile_image) }}" alt="Profile Picture"
                        class="rounded-circle" style="width: 70px; height: 70px; cursor: pointer"
                        data-bs-toggle="dropdown">
                @else
                    <div class="btn border-0 btn-secondary rounded-circle position-relative" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 70px; height: 70px; background-color: {{ $user->profile_color }}">
                        <p class="display-5 h-100 text-center position-absolute top-50 start-50 translate-middle">
                            {{ $user->name[0] }}
                        </p>
                    </div>
                @endif
                @if (auth()->id() == $user->id)
                    <a href="{{ route('account.delete.form') }}" class="btn btn-danger rounded-pill">Delete account</a>
                @else
                    <form class="m-0" id="follow-form">
                        @csrf
                        <input type="hidden" name="followed_user_id" value="{{ $user ? $user->id : '' }}">
                        <div class="p-0 m-0">
                            <button type="button" id="followBtn"
                                class="follow-btn rounded-pill {{ auth()->user()->isFollowing($user) ? 'border-success text-success' : 'btn-success' }} btn"
                                style="cursor: pointer;"
                                data-following="{{ auth()->user()->isFollowing($user) ? 'true' : 'false' }}"
                                data-user-id="{{ $user->id }}">
                                {{ auth()->user()->isFollowing($user) ? 'Following' : 'Follow' }}
                            </button>
                        </div>
                    </form>
                @endif
            </div>
            <p class="fw-semibold mt-2 mb-1">
                {{ $user->name }}
            </p>
            {{-- follower --}}
            <p class="m-0 mb-3">
                <a class="underline-hover text-dark"
                    href="{{ route('profile', ['username' => $user->username, 'menu' => 'followers']) }}">
                    {{ $user->followers()->count() }} Followers
                </a>
            </p>

            @if (auth()->check() && auth()->id() == $user->id)
                <a href="{{ route('profile.edit', ['username' => $user->username]) }}"
                    class="text-decoration-none text-success" style="cursor: pointer">
                    Edit profile
                </a>
            @endif

            {{-- following --}}
            <div>
                <p class="fw-semibold mt-2 mb-3 mt-4">Following</p>
                @foreach ($followedUsers as $follow)
                    <div class="d-flex mb-2 justify-content-between">
                        <a class="d-flex text-decoration-none text-dark gap-2"
                            href="{{ route('profile', ['username' => $follow->username]) }}" style="font-size: 0.9rem">
                            @if ($follow->profile_image)
                                <img src="{{ asset('profile_images/' . $follow->profile_image) }}" alt="Profile Picture"
                                    class="rounded-circle" style="width: 25px; height: 25px; cursor: pointer"
                                    data-bs-toggle="dropdown">
                            @else
                                <div id="profile-color"
                                    class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                    style="height: 25px; width: 25px; background-color: {{ $follow->profile_color }}">
                                    <span style="font-size: 0.9rem; pointer-events: none;"
                                        class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $follow->name[0] }}
                                    </span>
                                </div>
                            @endif
                            <p class="underline-hover mb-0">
                                {{ $follow->name }}
                            </p>
                        </a>
                    </div>
                @endforeach
                @if ($user->following->count() > 5)
                    <a class="underline-hover text-dark d-block mt-3" style="font-size: 0.9rem;"
                        href="{{ route('profile', ['username' => $user->username, 'menu' => 'following']) }}">
                        See all ({{ $user->following()->count() }})
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.save-button', function() {
                var $button = $(this);
                var postId = $button.closest('.saveForm').data('post-id');
                var isSaved = $button.data('saved') === true;

                $.ajax({
                    url: '{{ url(route('toggle.save')) }}',
                    type: 'POST',
                    data: {
                        post_id: postId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'saved') {
                            $button.data('saved', true);
                            $button.find('svg').attr('class', 'ajw')
                                .find('path').attr('d',
                                    'M7.5 3.75a2 2 0 0 0-2 2v14a.5.5 0 0 0 .8.4l5.7-4.4 5.7 4.4a.5.5 0 0 0 .8-.4v-14a2 2 0 0 0-2-2h-9z'
                                );
                        } else {
                            $button.data('saved', false);
                            $button.find('svg').attr('class', 'lm')
                                .find('path').attr('d',
                                    'M17.5 1.25a.5.5 0 0 1 1 0v2.5H21a.5.5 0 0 1 0 1h-2.5v2.5a.5.5 0 0 1-1 0v-2.5H15a.5.5 0 0 1 0-1h2.5v-2.5zm-11 4.5a1 1 0 0 1 1-1H11a.5.5 0 0 0 0-1H7.5a2 2 0 0 0-2 2v14a.5.5 0 0 0 .8.4l5.7-4.4 5.7 4.4a.5.5 0 0 0 .8-.4v-8.5a.5.5 0 0 0-1 0v7.48l-5.2-4a.5.5 0 0 0-.6 0l-5.2 4V5.75z'
                                );
                        }

                        // Tambahkan timeout sebelum reload untuk memberikan waktu pada perubahan visual
                        setTimeout(function() {
                            location.reload();
                        }, 200); // Delay 300ms
                    },
                    error: function(xhr, status, error) {
                        console.error("Error saving post:", error);
                        alert("An error occurred while saving the post. Please try again.");
                    }
                });
            });
        });
    </script>
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
