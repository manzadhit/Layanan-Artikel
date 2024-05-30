<style>
    #search:focus {
        outline: none;
    }
</style>

<nav class="navbar navbar-expand-lg mb-3 border-bottom sticky-top bg-white">
    <div class="container-fluid align-items-center">
        <div class="d-flex align-items-center">
            <a href="/" class="d-flex align-items-center gap-3 text-decoration-none me-3">
                <img class="rounded-circle" style="width: 1.8rem" src="{{ asset('img/trendzine.jpg') }}" alt="">
                <p class="navbar-brand m-0 mb-1 fw-semibold">TrendZine</p>
            </a>
            <div class="d-flex align-items-center justify-content-center px-3 py-1 gap-2 rounded-pill"
                style="background-color: #f9f9f9">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-search" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                </svg>
                <form class="d-flex m-auto" role="search" action="{{ route('posts.index') }}" method="GET">
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <input id="search" class="me-2 border-0 bg-transparent" type="search" placeholder="Search"
                        aria-label="Search" name="search" value="{{ request('search') }}"
                        onkeypress="if(event.keyCode == 13) { this.form.submit(); return false; }">
                </form>
            </div>
        </div>
        <div class="d-flex gap-5">
            <a class="ms-3 text-decoration-none d-flex gap-2 align-items-center" style="cursor: pointer; color: #6B6B6B"
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
            <div class="dropdown profile-menu">
                <div class="profile-button">
                    <button class="btn bg-primary border-fill" type="button" id="dropdownMenuButton" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        A
                    </button>
                </div>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Profile</a>
                    <a class="dropdown-item" href="#">Library</a>
                    <a class="dropdown-item" href="#">Stories</a>
                    <a class="dropdown-item" href="#">Stats</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="#">Refine recommendations</a>
                    <a class="dropdown-item" href="#">Manage publications</a>
                    <a class="dropdown-item" href="#">Help</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Become a Medium member</a>
                    <a class="dropdown-item" href="#">Create a Mastodon account</a>
                    <a class="dropdown-item" href="#">Apply for author verification</a>
                    <a class="dropdown-item" href="#">Apply to the Partner Program</a>
                    <a class="dropdown-item" href="#">Gift a membership</a>
                </div>
            </div>
        </div>
    </div>
</nav>
