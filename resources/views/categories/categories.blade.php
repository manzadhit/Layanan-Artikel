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
    <h1 class="text-center my-5">All Categories</h1>
    <div class="col-8 mx-auto">
        @foreach ($categories as $category)
            <div class="mb-3">
                <a class="d-flex text-decoration-none text-dark gap-3 align-items-center"
                    href="{{ route('category.posts', ['slug' => $category->slug]) }}" style="font-size: 0.9rem">
                    <div id="profile-color"
                        class="text-white border-0 btn-secondary rounded-circle position-relative flex-shrink-0"
                        style="height: 45px; width: 45px; background-color: #F2F2F2">
                        <span style="font-size: 2rem; pointer-events: none;"
                            class="m-0 text-center position-absolute top-50 start-50 translate-middle">
                            <svg style="color: black" width="16" height="16" viewBox="0 0 16 16" fill=""
                                class="rx">
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
                    <form class="m-0 ms-auto" action="{{ route('categories.follow', $category) }}" method="POST">
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
@endsection
