<!-- resources/views/notifications/index.blade.php -->
@extends('../template') {{-- Sesuaikan dengan layout aplikasi Anda --}}

@include('../partials/logged_in_navbar', ['user' => $user])

@section('content')
    <div class="col-8 border-end d-flex flex-column pe-5 pt-5">
        <h1 class="my-5 text-center">Notifications</h1>
        @foreach ($notifications as $index => $notification)
            <div class="d-flex gap-3 mb-3">
                <!-- Link gambar profil -->
                <a href="{{ route('profile', ['username' => $userResponded[$index]->username]) }}" class="flex-shrink-0">
                    @if ($userResponded[$index]->profile_image)
                        <img src="{{ asset('profile_images/' . $userResponded[$index]->profile_image) }}"
                            alt="Profile Picture" class="rounded-circle" style="width: 45px; height: 45px; cursor: pointer">
                    @else
                        <div id="profile-color" class="text-white border-0 btn-secondary rounded-circle position-relative"
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
                            {{ $notification->data['follower_name'] }}
                            <span class="text-secondary" style="font-size: .95rem">started following you.</span>
                        </p>
                    </a>
                    <small class="text-secondary m-0">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-4 pt-5 ps-5">
        {{-- profile name --}}
        <div class="d-flex align-items-center justify-content-between">
            @if ($user->profile_image)
                <img src="{{ asset('profile_images/' . $user->profile_image) }}" alt="Profile Picture"
                    class="rounded-circle" style="width: 70px; height: 70px; cursor: pointer" data-bs-toggle="dropdown">
            @else
                <div class="btn border-0 btn-secondary rounded-circle position-relative" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="width: 70px; height: 70px; background-color: {{ $user->profile_color }}">
                    <p class="display-5 h-100 text-center position-absolute top-50 start-50 translate-middle">
                        {{ $user->name[0] }}
                    </p>
                </div>
            @endif
            <form class="m-0" id="follow-form">
                @csrf
                <input type="hidden" name="followed_user_id" value="{{ $user ? $user->id : '' }}">
                <div class="p-0 m-0">
                    @if (auth()->check())
                        @if (auth()->id() != $user->id)
                            <button type="button" id="followBtn"
                                class="follow-btn rounded-pill {{ auth()->user()->isFollowing($user) ? 'border-success text-success' : 'btn-success' }} btn"
                                style="cursor: pointer;"
                                data-following="{{ auth()->user()->isFollowing($user) ? 'true' : 'false' }}"
                                data-user-id="{{ $user->id }}">
                                {{ auth()->user()->isFollowing($user) ? 'Following' : 'Follow' }}
                            </button>
                        @endif
                    @else
                        .<button type="button" id="loginToFollowBtn"
                            class="text-success text-decoration-none border-0 bg-transparent" style="cursor: pointer;">
                            Login to Follow
                        </button>
                    @endif
                </div>
            </form>
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
@endsection
