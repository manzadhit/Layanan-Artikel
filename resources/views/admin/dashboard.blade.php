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
    <div class="container">
        <div class="d-flex mb-3 gap-5 pt-4 border-bottom mx-auto align-items-center justify-content-center">
            <div class="pb-3 {{ $type == 'posts' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
                style="margin-bottom: -1px;">
                <a href="{{ route('dashboard', ['type' => 'posts', 'q' => request('q')]) }}" class="nav-link">Posts</a>
            </div>
            <div class=" pb-3 {{ $type == 'users' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
                style="margin-bottom: -1px;">
                <a href="{{ route('dashboard', ['type' => 'users', 'q' => request('q')]) }}" class="nav-link">Users</a>
            </div>
            <div class=" pb-3 {{ $type == 'categories' ? 'border-bottom border-black' : 'text-dark-emphasis' }}"
                style="margin-bottom: -1px;">
                <a href="{{ route('dashboard', ['type' => 'categories', 'q' => request('q')]) }}"
                    class="nav-link">Categories</a>
            </div>
        </div>
        @if (isset($results['posts']))

            @if ($results['posts']->isEmpty())
                <p class="text-center">No posts found</p>
            @else
                <h2 class='text-center m-0 my-5'>All posts</h2>
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Profile</th>
                            <th scope="col">Author</th>
                            <th scope="col">Title</th>
                            <th scope="col">Content</th>
                            <th scope="col">Image</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results['posts'] as $post)
                            <tr>
                                <td>
                                    <div>
                                        <a class="d-flex text-decoration-none text-dark gap-2"
                                            href="{{ route('profile', ['username' => $post->user->username]) }}"
                                            style="font-size: 0.9rem">
                                            @if ($post->user->profile_image)
                                                <div>
                                                    <img src="{{ asset('profile_images/' . $post->user->profile_image) }}"
                                                        alt="Profile Picture" class="rounded-circle"
                                                        style="width: 25px; height: 25px; cursor: pointer;">
                                                </div>
                                            @else
                                                <div id="profile-color"
                                                    class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                                    style="height: 25px; width: 25px; background-color: {{ $post->user->profile_color }};">
                                                    <span style="font-size: 0.9rem; pointer-events: none;"
                                                        class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $post->user->name[0] }}</span>
                                                </div>
                                            @endif
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    <a class="d-flex text-decoration-none text-dark gap-2"
                                        href="{{ route('profile', ['username' => $post->user->username]) }}"
                                        style="font-size: 0.9rem">
                                        <p class="underline-hover m-0" style="font-size: 1rem">
                                            {{ $post->user->name }}
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <p class="card-title underline-hover">
                                        <a class="text-decoration-none text-dark"
                                            href="/posts/{{ $post->slug }}">{{ Str::limit($post->title, 50) }}</a>
                                    </p>
                                </td>
                                <td>
                                    <p class="card-text underline-hover">
                                        <a class="text-decoration-none text-dark" href="/posts/{!! $post->slug !!}">
                                            {!! Str::limit($getFirstTagRegex($post->content), 130) !!}
                                        </a>
                                    </p>
                                </td>
                                <td>
                                    <div>
                                        @if ($post->postImages->isNotEmpty())
                                            {{-- Ambil gambar pertama dari koleksi gambar --}}
                                            @php
                                                $image = $post->postImages->first(); // Ambil objek gambar pertama
                                            @endphp
                                            <img src="{{ asset('images/' . $image->image_name) }}"
                                                style="height: 50px; width: 90%" alt="{{ $post->title }}">
                                        @else
                                            {{-- Placeholder jika tidak ada gambar --}}
                                            <img src="https://picsum.photos/seed/picsum/200/300"
                                                style="height: 50px; width: 90%" alt="Placeholder Image">
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('posts.destroy', $post->slug) }}" method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Anda yakin ingin menghapus post ini?')"
                                            type="submit" class="btn btn-danger d-flex align-items-center gap-2"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        @elseif(isset($results['users']))
            @if ($results['users']->isEmpty())
                <p class="text-center">No users found</p>
            @else
                <h2 class='text-center m-0 my-5'>All users</h2>

                <div class="d-flex flex-column col-8 mx-auto">
                    @foreach ($results['users'] as $user)
                        @if ($user->id !== auth()->id())
                            <div class="mb-3">
                                <a class="d-flex text-decoration-none text-dark gap-3"
                                    href="{{ route('profile', ['username' => $user->username]) }}"
                                    style="font-size: 0.9rem">
                                    @if ($user->profile_image)
                                        <img src="{{ asset('profile_images/' . $user->profile_image) }}"
                                            alt="Profile Picture" class="rounded-circle"
                                            style="width: 45px; height: 45px; cursor: pointer" data-bs-toggle="dropdown">
                                    @else
                                        <div id="profile-color"
                                            class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                                            style="height: 45px; width: 45px; background-color: {{ $user->profile_color }}">
                                            <span style="font-size: 2rem; pointer-events: none;"
                                                class="m-0 text-center position-absolute top-50 start-50 translate-middle">{{ $user->name[0] }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="d-flex flex-column">
                                        <p class="underline-hover m-0 fs-5 my-auto">
                                            {{ $user->name }}
                                        </p>
                                        <div class="d-flex">
                                            <p class="m-0 text-secondary">{{ $user->followers()->count() }} Followers
                                            </p>
                                            <span class="mx-1 align-middle">•</span>
                                            <p class="m-0 text-secondary">{{ $user->posts()->count() }} Posts</p>
                                        </div>
                                    </div>
                                    <form class="ms-auto"
                                        action="{{ route('dashboard.user.delete', ['type' => 'users', 'user' => $user->id]) }}"
                                        method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button onclick="return confirm('Anda yakin ingin menghapus user ini?')"
                                            type="submit" class="btn btn-danger d-flex align-items-center gap-2"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                <path
                                                    d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        @elseif(isset($results['categories']))
            @if ($results['categories']->isEmpty())
                <p class="text-center">No categories found</p>
            @else
                <h2 class='text-center m-0 my-5'>All categories</h2>
                <div class="col-8 mx-auto">
                    @foreach ($results['categories'] as $category)
                        <div class="mb-3 d-flex justify-content-between">
                            <a class="d-flex text-decoration-none text-dark gap-3 align-items-center"
                                href="{{ route('category.posts', ['slug' => $category->slug]) }}"
                                style="font-size: 0.9rem">
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
                            </a>
                            <div class="d-flex gap-3">
                                <a class="text-decoration-none align-items-center" style="cursor: pointer; color: #6B6B6B"
                                    href="{{ route('categories.edit', $category->slug) }}">
                                    <button type="submit" class="btn btn-primary d-flex gap-2 ">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path
                                                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                            <path fill-rule="evenodd"
                                                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                        </svg>
                                        <p class="m-0">Edit</p>
                                    </button>
                                </a>
                                <form
                                    action="{{ route('dashboard.category.delete', ['type' => 'categories', 'category' => $category->slug]) }}"
                                    method="POST" class="ms-auto"
                                    onsubmit="return confirm('Anda yakin ingin menghapus kategori ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                            <path
                                                d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <p>No results found</p>
        @endif
    </div>
@endsection
