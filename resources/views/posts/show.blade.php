@extends('template')

@section('title', 'Show Post - TrendZine')

<!-- navbar.blade.php -->
@if (auth()->check())
    <!-- Navbar untuk pengguna yang sudah login -->
    @include('partials.logged_in_navbar', ['user' => $user])
@else
    <!-- Navbar untuk pengguna yang belum login -->
    @include('partials.logged_out_navbar')
@endif


@section('styles')
    <style>
        body {
            padding-bottom: 5%;
        }

        .post-content-image {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        figure.image {
            text-align: center;
            margin: 20px 0;
        }
    </style>
@endsection


@section('content')
    <div class="row">
        <div class="col-8 mt-4 mx-auto d-flex flex-column gap-4">
            <h1 class="fw-bold">{{ $post->title }}</h1>
            <div class="d-flex gap-3">
                <div>
                    <img class="rounded-circle" src="{{ asset('img/profile.jpg') }}" alt="">
                </div>
                {{-- follow-form --}}
                <div>
                    <form class="m-0" id="follow-form">
                        @csrf
                        <input type="hidden" name="followed_user_id" value="{{ $post->user ? $post->user->id : '' }}">
                        <p class="p-0 m-0">
                            {{ $post->user->name }}
                            @if (auth()->check())
                                @if (auth()->id() != $post->user->id)
                                    .<button type="button" id="followBtn"
                                        class="follow-btn text-success text-decoration-none border-0 bg-transparent"
                                        style="cursor: pointer;"
                                        data-following="{{ auth()->user()->isFollowing($post->user)? 'true': 'false' }}"
                                        data-user-id="{{ $post->user->id }}">
                                        {{ auth()->user()->isFollowing($post->user)? 'Following': 'Follow' }}
                                    </button>
                                @endif
                            @else
                                <button type="button" id="loginToFollowBtn"
                                    class="text-success text-decoration-none border-0 bg-transparent"
                                    style="cursor: pointer;">
                                    Login to Follow
                                </button>
                            @endif
                        </p>
                    </form>
                    <small class="text-secondary">{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>
            <div class="border border-start-0 border-end-0 p-3 d-flex justify-content-between">
                {{-- like form --}}
                <div class="d-flex gap-4">
                    <div class="d-flex gap-1">
                        <svg id="like-button" data-post-id="{{ $post->id }}" width="24" height="24"
                            style="cursor: pointer" viewBox="0 0 24 24" aria-label="clap">
                            <path fill="{{ $isLiked ? 'red' : 'grey' }}" fill-rule="evenodd" clip-rule="evenodd"
                                d="M11.37.83L12 3.28l.63-2.45h-1.26zM13.92 3.95l1.52-2.1-1.18-.4-.34 2.5zM8.59 1.84l1.52 2.11-.34-2.5-1.18.4zM18.52 18.92a4.23 4.23 0 0 1-2.62 1.33l.41-.37c2.39-2.4 2.86-4.95 1.4-7.63l-.91-1.6-.8-1.67c-.25-.56-.19-.98.21-1.29a.7.7 0 0 1 .55-.13c.28.05.54.23.72.5l2.37 4.16c.97 1.62 1.14 4.23-1.33 6.7zm-11-.44l-4.15-4.15a.83.83 0 0 1 1.17-1.17l2.16 2.16a.37.37 0 0 0 .51-.52l-2.15-2.16L3.6 11.2a.83.83 0 0 1 1.17-1.17l3.43 3.44a.36.36 0 0 0 .52 0 .36.36 0 0 0 0-.52L5.29 9.51l-.97-.97a.83.83 0 0 1 0-1.16.84.84 0 0 1 1.17 0l.97.97 3.44 3.43a.36.36 0 0 0 .51 0 .37.37 0 0 0 0-.52L6.98 7.83a.82.82 0 0 1-.18-.9.82.82 0 0 1 .76-.51c.22 0 .43.09.58.24l5.8 5.79a.37.37 0 0 0 .58-.42L13.4 9.67c-.26-.56-.2-.98.2-1.29a.7.7 0 0 1 .55-.13c.28.05.55.23.73.5l2.2 3.86c1.3 2.38.87 4.59-1.29 6.75a4.65 4.65 0 0 1-4.19 1.37 7.73 7.73 0 0 1-4.07-2.25zm3.23-12.5l2.12 2.11c-.41.5-.47 1.17-.13 1.9l.22.46-3.52-3.53a.81.81 0 0 1-.1-.36c0-.23.09-.43.24-.59a.85.85 0 0 1 1.17 0zm7.36 1.7a1.86 1.86 0 0 0-1.23-.84 1.44 1.44 0 0 0-1.12.27c-.3.24-.5.55-.58.89-.25-.25-.57-.4-.91-.47-.28-.04-.56 0-.82.1l-2.18-2.18a1.56 1.56 0 0 0-2.2 0c-.2.2-.33.44-.4.7a1.56 1.56 0 0 0-2.63.75 1.6 1.6 0 0 0-2.23-.04 1.56 1.56 0 0 0 0 2.2c-.24.1-.5.24-.72.45a1.56 1.56 0 0 0 0 2.2l.52.52a1.56 1.56 0 0 0-.75 2.61L7 19a8.46 8.46 0 0 0 4.48 2.45 5.18 5.18 0 0 0 3.36-.5 4.89 4.89 0 0 0 4.2-1.51c2.75-2.77 2.54-5.74 1.43-7.59L18.1 7.68z">
                            </path>
                        </svg>
                        <p class="m-0" id="like-count">{{ $post->likes->count() }}</p>
                    </div>
                </div>
                <div class="d-flex gap-4">
                    {{-- save form --}}
                    <svg id="save-button" width="24" height="24" style="cursor: pointer" viewBox="0 0 24 24"
                        fill="none" class="ni">
                        <path
                            d="M17.5 1.25a.5.5 0 0 1 1 0v2.5H21a.5.5 0 0 1 0 1h-2.5v2.5a.5.5 0 0 1-1 0v-2.5H15a.5.5 0 0 1 0-1h2.5v-2.5zm-11 4.5a1 1 0 0 1 1-1H11a.5.5 0 0 0 0-1H7.5a2 2 0 0 0-2 2v14a.5.5 0 0 0 .8.4l5.7-4.4 5.7 4.4a.5.5 0 0 0 .8-.4v-8.5a.5.5 0 0 0-1 0v7.48l-5.2-4a.5.5 0 0 0-.6 0l-5.2 4V5.75z"
                            fill="#000"></path>
                    </svg>
                    {{-- share form --}}
                    <svg id="share-button" width="24" height="24" style="cursor: pointer" viewBox="0 0 24 24"
                        fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M15.22 4.93a.42.42 0 0 1-.12.13h.01a.45.45 0 0 1-.29.08.52.52 0 0 1-.3-.13L12.5 3v7.07a.5.5 0 0 1-.5.5.5.5 0 0 1-.5-.5V3.02l-2 2a.45.45 0 0 1-.57.04h-.02a.4.4 0 0 1-.16-.3.4.4 0 0 1 .1-.32l2.8-2.8a.5.5 0 0 1 .7 0l2.8 2.8a.42.42 0 0 1 .07.5zm-.1.14zm.88 2h1.5a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-11a2 2 0 0 1-2-2v-10a2 2 0 0 1 2-2H8a.5.5 0 0 1 .35.14c.1.1.15.22.15.35a.5.5 0 0 1-.15.35.5.5 0 0 1-.35.15H6.4c-.5 0-.9.4-.9.9v10.2a.9.9 0 0 0 .9.9h11.2c.5 0 .9-.4.9-.9V8.96c0-.5-.4-.9-.9-.9H16a.5.5 0 0 1 0-1z"
                            fill="currentColor"></path>
                    </svg>
                    {{-- other form --}}
                    @if (auth()->check())
                        <div class="dropdown">
                            <svg id="report-button" data-bs-toggle="dropdown" width="24" height="24"
                                style="cursor: pointer" viewBox="0 0 24 24" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4.39 12c0 .55.2 1.02.59 1.41.39.4.86.59 1.4.59.56 0 1.03-.2 1.42-.59.4-.39.59-.86.59-1.41 0-.55-.2-1.02-.6-1.41A1.93 1.93 0 0 0 6.4 10c-.55 0-1.02.2-1.41.59-.4.39-.6.86-.6 1.41zM10 12c0 .55.2 1.02.58 1.41.4.4.87.59 1.42.59.54 0 1.02-.2 1.4-.59.4-.39.6-.86.6-1.41 0-.55-.2-1.02-.6-1.41a1.93 1.93 0 0 0-1.4-.59c-.55 0-1.04.2-1.42.59-.4.39-.58.86-.58 1.41zm5.6 0c0 .55.2 1.02.57 1.41.4.4.88.59 1.43.59.57 0 1.04-.2 1.43-.59.39-.39.57-.86.57-1.41 0-.55-.2-1.02-.57-1.41A1.93 1.93 0 0 0 17.6 10c-.55 0-1.04.2-1.43.59-.38.39-.57.86-.57 1.41z"
                                    fill="currentColor"></path>
                            </svg>
                            <ul class="dropdown-menu" id="report-dropdown" style="min-width: 120px; font-size: .95rem">
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
                                        <form action="{{ route('posts.destroy', $post->slug) }}" method="POST"
                                            class="mb-0">
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
            {{-- Content --}}
            <div>
                <p>{!! $post->content !!}</p>
            </div>
            {{-- Categories --}}
            <div class="d-flex">
                @foreach ($post->categories as $category)
                    <div class="px-3 rounded-pill me-2" style="background-color: #f6f6f6">
                        {{ $category->name }}
                    </div>
                @endforeach
            </div>
            {{-- Comments --}}
            <div class="row d-flex justify-content-center mt-4">
                <h2 class="mb-3">Comment({{ $comments->count() }})</h2>
                <div class="col-md-12">
                    @if (auth()->check())
                        <div class="card-footer py-3 border-0">
                            <form id="postForm" action="{{ route('comment.store', ['postId' => $post->id]) }}"
                                method="POST">
                                @csrf
                                <div class="d-flex flex-start w-100">
                                    <div class="btn me-3 btn-secondary rounded-circle text-center position-relative"
                                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                        style="width: 40px; height: 40px">
                                        <span class="position-absolute fs-5 text"
                                            style="top: 1; left: 13.5">{{ $post->user->name[0] }}</span>
                                    </div>
                                    <div data-mdb-input-init class="form-outline w-100">
                                        <textarea class="form-control" id="comment" name="comment" rows="4" style="background: #fff;"
                                            placeholder="message..">{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="float-end mt-2 pt-1">
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-primary btn-sm">Post comment</button>
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm">Cancel</button>
                                </div>
                            </form>
                        </div>
                    @endif
                    <div class="card mt-5 border-0">
                        @foreach ($comments as $comment)
                            <div class="card-body border mb-1 rounded">
                                <div class="d-flex  justify-content-between align-items-center">
                                    <div class="d-flex flex-start align-items-center">
                                        <div class="btn mx-2 btn-secondary rounded-circle text-center position-relative"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                                            style="width: 40px; height: 40px">
                                            <span class="position-absolute fs-5 text"
                                                style="top: 1; left: 13.5">{{ $post->user->name[0] }}</span>
                                        </div>
                                        <div>
                                            <p class="mb-1">{{ $comment->user->name }}</p>
                                            <small class="text-secondary">{{ $post->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    @if (auth()->check())
                                        <div class="dropdown">
                                            <svg id="report-button" data-bs-toggle="dropdown" width="24"
                                                height="24" style="cursor: pointer" viewBox="0 0 24 24"
                                                fill="none">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M4.39 12c0 .55.2 1.02.59 1.41.39.4.86.59 1.4.59.56 0 1.03-.2 1.42-.59.4-.39.59-.86.59-1.41 0-.55-.2-1.02-.6-1.41A1.93 1.93 0 0 0 6.4 10c-.55 0-1.02.2-1.41.59-.4.39-.6.86-.6 1.41zM10 12c0 .55.2 1.02.58 1.41.4.4.87.59 1.42.59.54 0 1.02-.2 1.4-.59.4-.39.6-.86.6-1.41 0-.55-.2-1.02-.6-1.41a1.93 1.93 0 0 0-1.4-.59c-.55 0-1.04.2-1.42.59-.4.39-.58.86-.58 1.41zm5.6 0c0 .55.2 1.02.57 1.41.4.4.88.59 1.43.59.57 0 1.04-.2 1.43-.59.39-.39.57-.86.57-1.41 0-.55-.2-1.02-.57-1.41A1.93 1.93 0 0 0 17.6 10c-.55 0-1.04.2-1.43.59-.38.39-.57.86-.57 1.41z"
                                                    fill="currentColor"></path>
                                            </svg>
                                            <ul class="dropdown-menu" id="report-dropdown"
                                                style="min-width: 120px; font-size: .95rem">
                                                @if (auth()->check() && auth()->id() == $comment->user_id)
                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center text-center edit-comment-btn"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-comment-content="{{ $comment->content }}">
                                                            Edit comment
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('comment.delete', $comment->id) }}"
                                                            method="POST" class="mb-0">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center text-danger"
                                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                Delete comment
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button type="button"
                                                            class="dropdown-item d-flex align-items-center text-center text-danger"
                                                            id="reportCommentBtn" data-comment-id="{{ $comment->id }}">
                                                            Report comment
                                                        </button>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <p class="mt-3 mb-4 ps-2">
                                    {{ $comment->content }}
                                </p>

                                {{-- <div class="small d-flex justify-content-start">
                                    <a href="#!" class="d-flex align-items-center me-3 text-decoration-none">
                                        <i class="far fa-thumbs-up me-2"></i>
                                        <p class="mb-0">Like</p>
                                    </a>
                                    <a href="#!" class="text-decoration-none d-flex align-items-center me-3">
                                        <i class="far fa-comment-dots me-2"></i>
                                        <p class="mb-0">Reply</p>
                                    </a>
                                </div> --}}
                                <!-- Edit Comment Modal -->
                                <div class="modal fade" id="editCommentModal" tabindex="-1"
                                    aria-labelledby="editCommentModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCommentModalLabel">Edit Comment</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form id="editCommentForm" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <textarea class="form-control" id="editCommentContent" name="content" rows="3" required>{{ old('content') }}</textarea>
                                                    @error('content')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save
                                                        changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Handle edit comment button click
            $('.edit-comment-btn').on('click', function() {
                var commentId = $(this).data('comment-id');
                var commentContent = $(this).data('comment-content');

                $('#editCommentContent').val(commentContent);
                $('#editCommentForm').attr('action', '/comments/' + commentId);

                $('#editCommentModal').modal('show');
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

                @if (auth()->check())
                    var authUserId = {{ auth()->id() }};
                    $.ajax({
                        url: "{{ route('toggle.follow', ['userId' => ':userId']) }}".replace(
                            ':userId', authUserId),
                        method: 'POST',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            followed_user_id: userId
                        },
                        success: function(response) {
                            if (response.isFollowing) {
                                btn.text('Following').data('following', 'true');
                            } else {
                                btn.text('Follow').data('following', 'false');
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr.responseText);
                            alert('An error occurred. Please try again.');
                        }
                    });
                @else
                    // Jika pengguna belum login, arahkan ke halaman login
                    window.location.href = "{{ route('login') }}";
                @endif
            });

            // Handler untuk tombol "Login to Follow"
            $(document).on('click', '#loginToFollowBtn', function() {
                window.location.href = "{{ route('login') }}";
            });
        });
    </script>
    <script>
        var isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};
    </script>


    <script>
        $('#report-button, #share-button, #save-button').on('click', function() {
            if (!isLoggedIn) {
                window.location.href = '{{ route('login') }}';
                return;
            }
        })
        $(document).ready(function() {
            $('#like-button').on('click', function() {
                if (!isLoggedIn) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                var postId = $(this).data('post-id');

                $.ajax({
                    url: '{{ route('toggle.like') }}',
                    type: 'POST',
                    data: {
                        post_id: postId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'liked') {
                            $('#like-button path').attr('fill', 'red');
                        } else {
                            $('#like-button path').attr('fill', 'grey');
                        }
                        $('#like-count').text(response.likeCount);
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {

            document.getElementById('comment-button').addEventListener('click', function() {
                // Tambahkan logika untuk menangani "comment" di sini
            });

            document.getElementById('save-button').addEventListener('click', function() {
                alert('Save button clicked!');
                // Tambahkan logika untuk menangani "save" di sini
            });

            document.getElementById('share-button').addEventListener('click', function() {
                alert('Share button clicked!');
                // Tambahkan logika untuk menangani "share" di sini
            });

            document.getElementById('report-button').addEventListener('click', function() {
                alert('Report button clicked!');
                // Tambahkan logika untuk menangani "report" di sini
            });
        });
    </script>
@endpush
