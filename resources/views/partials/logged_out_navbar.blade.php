<nav class="navbar navbar-expand-lg mb-3 px-4 sticky-top bg-white">
    <div class="container-fluid align-items-center">
        <a href="/" class="navbar-brand m-0 fw-semibold">TrendZine</a>
        <div class="d-flex gap-3">
            <a class="text-decoration-none d-flex gap-2 align-items-center" style="cursor: pointer; color: #6B6B6B"
                href="{{ route('login') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M6.5 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 0-1 0v1A1.5 1.5 0 0 0 6.5 13h8A1.5 1.5 0 0 0 16 11.5v-8A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v1a.5.5 0 0 0 1 0v-1zM.5 5.646l3-3a.5.5 0 0 1 .708.708L1.707 5H10.5a.5.5 0 0 1 0 1H1.707l2.5 2.5a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708z" />
                    <path fill-rule="evenodd"
                        d="M3.5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM10 6.646l2.5 2.5a.5.5 0 0 1-.708.708L9.5 7.707V9.5a.5.5 0 0 1-1 0V6.707L6.207 9.854a.5.5 0 1 1-.708-.708l2.5-2.5a.5.5 0 0 1 .708 0z" />
                </svg>
                <p class="m-0">Login</p>
            </a>
            <a class="text-decoration-none d-flex gap-2 align-items-center" style="cursor: pointer; color: #6B6B6B"
                href="{{ route('register') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-person-plus" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M9 10a3 3 0 0 1-6 0 3 3 0 0 1 6 0zm4-3a4 4 0 1 0-4 4 4 4 0 0 0 4-4zm-.355 5.632a5.48 5.48 0 0 0-1.026-.757 5.5 5.5 0 1 0 1.654.104zM9 8a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-1-6a3 3 0 0 0-3 3c0 .256.036.501.1.736a3.002 3.002 0 0 0-1.033 2.264A3.001 3.001 0 0 0 8 14a3 3 0 0 0 2.898-2.265c.073-.235.102-.48.102-.735a3 3 0 0 0-3-3zm7.5-1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V2.5a.5.5 0 0 1 .5-.5zm0 5a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V7.5a.5.5 0 0 1 .5-.5z" />
                </svg>
                <p class="m-0">Register</p>
            </a>
        </div>
    </div>
</nav>
