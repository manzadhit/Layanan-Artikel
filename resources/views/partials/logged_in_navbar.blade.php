<style>
    #search:focus {
        outline: none;
    }
</style>

<nav class="navbar navbar-expand-lg px-4 justify-content-between sticky-top bg-white border border-bottom">
    <div class="align-items-center">
        <div class="d-flex align-items-center">
            <a href="/" class="d-flex align-items-center gap-3 text-decoration-none me-3">
                <img class="rounded-circle" style="width: 1.8rem" src="{{ asset('img/trendzine.jpg') }}" alt="">
                <p class="navbar-brand m-0 mb-1 fw-semibold">TrendZine</p>
            </a>
            <!-- Search Bar -->
            @if (Auth::user()->role == 'admin' && request()->is('dashboard*'))
                <!-- Search Bar -->
                <div class="d-flex align-items-center justify-content-center px-3 py-1 gap-2 rounded-pill"
                    style="background-color: #f9f9f9">
                    <!-- Search Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                    <!-- Search Form -->
                    <form class="d-flex m-auto" role="search"
                        action="{{ route('dashboard', ['type' => request('type', 'posts')]) }}" method="GET">
                        <input id="search" class="me-2 border-0 bg-transparent" type="search" placeholder="Search"
                            aria-label="Search" name="q" value="{{ request('q') }}"
                            onkeypress="if(event.keyCode == 13) { this.form.submit(); return false; }">
                    </form>
                </div>
            @else
                <div class="d-flex align-items-center justify-content-center px-3 py-1 gap-2 rounded-pill"
                    style="background-color: #f9f9f9">
                    <!-- Search Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg>
                    <!-- Search Form -->
                    <form class="d-flex m-auto" role="search"
                        action="{{ route('search.type', ['type' => request('type', 'posts')]) }}" method="GET">
                        <input id="search" class="me-2 border-0 bg-transparent" type="search" placeholder="Search"
                            aria-label="Search" name="q" value="{{ request('q') }}"
                            onkeypress="if(event.keyCode == 13) { this.form.submit(); return false; }">
                    </form>
                </div>
            @endif
        </div>
    </div>
    <div class="d-flex gap-3 align-items-center">
        @if (Auth::user()->role == 'admin' && request()->is('dashboard*'))
            <!-- Create Category Button -->
            <a class="text-decoration-none d-flex gap-2 align-items-center" style="cursor: pointer; color: #6B6B6B"
                href="{{ route('categories.create') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path
                        d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                    <path fill-rule="evenodd"
                        d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                </svg>
                <p class="m-0">Create Category</p>
            </a>
        @endif
        <!-- Write Button -->
        <a class="text-decoration-none d-flex gap-2 align-items-center" style="cursor: pointer; color: #6B6B6B"
            href="{{ route('posts.create') }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path
                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd"
                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
            </svg>
            <p class="m-0">Write</p>
        </a>
        <a href="{{ route('profile', ['username' => $user->username, 'menu' => 'notifications']) }}"
            class="text-decoration-none text-dark position-relative">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-bell"
                viewBox="0 0 16 16">
                <path
                    d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6" />
            </svg>
            @if ($user->unreadNotifications()->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="transform: translate(-50%, -50%);">
                    {{ $user->unreadNotifications()->count() }}
                    <span class="visually-hidden">unread messages</span>
                </span>
            @endif
        </a>
        <!-- Dropdown Menu -->
        <div class="dropdown-center">
            @if ($user->profile_image)
                <img src="{{ asset('profile_images/' . $user->profile_image) }}" alt="Profile Picture"
                    class="rounded-circle" style="width: 40px; height: 40px; cursor: pointer" data-bs-toggle="dropdown">
            @else
                <div class="btn btn-secondary border-0 rounded-circle text-center position-relative" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false"
                    style="width: 40px; height: 40px; background-color: {{ $user->profile_color }}">
                    <p class="position-absolute h-100 text top-50 start-50 translate-middle" style="font-size: 1.4rem">
                        {{ $user->name[0] }}</p>
                </div>
            @endif
            <ul class="dropdown-menu dropdown-menu-end z-1">
                @if (Auth::user()->role == 'admin')
                    <li>
                        <a class="m-0 text-decoration-none" id="profile-form"
                            href="{{ route('dashboard', ['type' => 'posts']) }}">
                            @csrf

                            <button type="submit" class="dropdown-item d-flex gap-2 align-items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z" />
                                    <path fill-rule="evenodd"
                                        d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3" />
                                </svg>
                                Dashboard</button>
                        </a>
                    </li>
                @endif
                <li>
                    <a class="m-0 text-decoration-none" id="profile-form"
                        href="{{ route('profile', ['username' => $user->username]) }}">
                        @csrf

                        <button type="submit" class="dropdown-item d-flex align-items-center">
                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            Profile</button>
                    </a>
                </li>
                <li>
                    <form class="m-0" id="profile" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center">
                            <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                                <path fill-rule="evenodd"
                                    d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>

    </div>
</nav>
