<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg bg-body-tertiary mb-3">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Kelompok 6</a>
            <div class="collapse navbar-collapse ms-5" id="navbarSupportedContent">
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3>Halaman Post</h3>
        <div class="d-flex mb-3 border-bottom">
            @foreach ($categories as $category)
                <div class="me-5 p-2 border-bottom">{{ $category->name }}</div>
            @endforeach
        </div>
        @foreach ($posts as $post)
            <div class="card w-75 mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p>By. {{ $post->user->name }} <span class=""> {{ \Carbon\Carbon::parse($post->created_at)->format('M d, Y') }}</span></p> 
                    <p class="card-text">{{ Str::limit($post->content, 300, '...') }}</p>
                    <a href="#" class="btn btn-primary">Read more</a>
                </div>
            </div>
        @endforeach
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
