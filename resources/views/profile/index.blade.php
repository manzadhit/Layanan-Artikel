@extends('../template')

@include('partials.logged_in_navbar', ['user' => $user])


@section('content')
    <div class="d-flex h-100 w-100 row">
        {{-- all post info --}}
        <div class="col-8 border-end-0 d-flex flex-column pe-5 pt-5">
            <div class="d-flex justify-content-between align-items-center ">
                <h1 class="m-0">Nyoman</h1>
                <svg data-bs-toggle="dropdown" width="24" height="24" style="cursor: pointer" viewBox="0 0 24 24"
                    fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.39 12c0 .55.2 1.02.59 1.41.39.4.86.59 1.4.59.56 0 1.03-.2 1.42-.59.4-.39.59-.86.59-1.41 0-.55-.2-1.02-.6-1.41A1.93 1.93 0 0 0 6.4 10c-.55 0-1.02.2-1.41.59-.4.39-.6.86-.6 1.41zM10 12c0 .55.2 1.02.58 1.41.4.4.87.59 1.42.59.54 0 1.02-.2 1.4-.59.4-.39.6-.86.6-1.41 0-.55-.2-1.02-.6-1.41a1.93 1.93 0 0 0-1.4-.59c-.55 0-1.04.2-1.42.59-.4.39-.58.86-.58 1.41zm5.6 0c0 .55.2 1.02.57 1.41.4.4.88.59 1.43.59.57 0 1.04-.2 1.43-.59.39-.39.57-.86.57-1.41 0-.55-.2-1.02-.57-1.41A1.93 1.93 0 0 0 17.6 10c-.55 0-1.04.2-1.43.59-.38.39-.57.86-.57 1.41z"
                        fill="currentColor"></path>
                </svg>
            </div>
            {{-- menu --}}
            <div class="mt-4">
                <div class="d-flex mb-4 border-bottom">
                    <div class="me-5 pb-3" style="margin-bottom: -1px; font-size: .95rem">
                        <a href="/" class="nav-link">For you</a>
                    </div>
                    <div class="me-5 pb-3" style="margin-bottom: -1px">
                        <a href="/" class="nav-link">For you</a>
                    </div>
                    <div class="me-5 pb-3" style="margin-bottom: -1px">
                        <a href="/" class="nav-link">For you</a>
                    </div>
                </div>
            </div>
            {{-- all post --}}
            @if ($posts->count())
                <div class="mx-auto">
                    @foreach ($posts as $post)
                        <div class="card w-100 mb-5 border-0">
                            <div class="card-body d-flex gap-2 p-0">
                                <div class="col-9">
                                    <p class="mb-2">
                                        <a class="text-decoration-none text-dark"
                                            href="/posts?author={{ $post->user->name }}">{{ $post->user->name }}</a>.
                                        <small class="text-secondary">{{ $post->created_at->diffForHumans() }}</small>
                                    </p>
                                    <h5 class="card-title">
                                        <a class="text-decoration-none text-dark"
                                            href="/posts/{{ $post->slug }}">{{ $post->title }}</a>
                                    </h5>
                                    <p class="card-text">
                                        <a class="text-decoration-none text-dark" href="/posts/{!! $post->slug !!}">
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
                                        <svg class="me-5" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                            <path
                                                d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-3 d-flex align-items-center">
                                    @if ($post->postImages->isNotEmpty())
                                        {{-- Ambil gambar pertama dari koleksi gambar --}}
                                        @php
                                            $image = $post->postImages->first(); // Ambil objek gambar pertama
                                        @endphp
                                        <img src="{{ asset('images/' . $image->image_name) }}"
                                            style="height: 150px; width: 100%" alt="">
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
        {{-- profile info --}}
        <div class="col-4 p-5">
            {{-- profile name --}}
            <div class="btn btn-secondary rounded-circle position-relative" type="button" data-bs-toggle="dropdown"
                aria-expanded="false" style="width: 70px; height: 70px">
                <p class="m-0 display-5 text-center position-absolute top-50 start-50 translate-middle"
                    style="bottom: -35px">{{ $user->name[0] }}
                </p>
            </div>
            <p class="fw-semibold mt-2 mb-1">
                {{ $user->name }}
            </p>
            {{-- follower --}}
            <p class="m-0 mb-3">
                {{ $user->followers()->count() }} Followers
            </p>

            <a href="$" class="text-decoration-none text-success" style="cursor: pointer">
                Edit profile
            </a>

            {{-- following --}}
            <div>
                <p class="fw-semibold mt-2 mb-3 mt-4">Following</p>
                @foreach ($followedUsers as $follow)
                    <div class="d-flex gap-2">
                        <div class="btn btn-secondary rounded-circle position-relative" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false" style="height: 25px">
                            <span style="font-size: 0.7rem"
                                class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $follow->name[0] }}
                            </span>
                        </div>
                        <p style="font-size: 0.9rem">{{ $follow->name }}</p>
                        <svg class="ms-auto" data-bs-toggle="dropdown" width="24" height="24" style="cursor: pointer"
                            viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M4.39 12c0 .55.2 1.02.59 1.41.39.4.86.59 1.4.59.56 0 1.03-.2 1.42-.59.4-.39.59-.86.59-1.41 0-.55-.2-1.02-.6-1.41A1.93 1.93 0 0 0 6.4 10c-.55 0-1.02.2-1.41.59-.4.39-.6.86-.6 1.41zM10 12c0 .55.2 1.02.58 1.41.4.4.87.59 1.42.59.54 0 1.02-.2 1.4-.59.4-.39.6-.86.6-1.41 0-.55-.2-1.02-.6-1.41a1.93 1.93 0 0 0-1.4-.59c-.55 0-1.04.2-1.42.59-.4.39-.58.86-.58 1.41zm5.6 0c0 .55.2 1.02.57 1.41.4.4.88.59 1.43.59.57 0 1.04-.2 1.43-.59.39-.39.57-.86.57-1.41 0-.55-.2-1.02-.57-1.41A1.93 1.93 0 0 0 17.6 10c-.55 0-1.04.2-1.43.59-.38.39-.57.86-.57 1.41z"
                                fill="currentColor"></path>
                        </svg>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
