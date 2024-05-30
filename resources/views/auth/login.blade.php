@extends('../template')

@section('title', 'Login')

@section('content')
<section class="p-3 p-md-4 h d-flex justify-content-center align-items-center" style="height: 100vh">
  <div class="container">
    <div class="card border-light-subtle shadow-sm">
      <div class="row g-0">
        <div class="col-12 col-md-6">
          <div class="card-body p-3 p-md-4 p-xl-5">
            <div class="row">
              <div class="col-12">
                <div class="mb-3">
                  <h2 class="h3">Login</h2>
                  <h3 class="fs-6 fw-normal text-secondary m-0">Enter your credentials to login</h3>
                </div>
              </div>
            </div>
            <form action="{{ route('login') }}" method="POST">
              @csrf
              <div class="row gy-3 gy-md-2 overflow-hidden">
                <div class="col-12">
                  <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                  <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                  @error('email')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12">
                  <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="password" id="password" required>
                  @error('password')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="col-12">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="remember" id="remember">
                    <label for="remember" class="form-check-label">Remember Me</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="d-grid">
                    <button class="btn bsb-btn-xl btn-primary" type="submit">Login</button>
                  </div>
                </div>
              </div>
            </form>
            <div class="row">
              <div class="col-12">
                <hr class="mt-2 mb-4 border-secondary-subtle">
                <p class="m-0 text-secondary text-center">Don't have an account? <a href="{{ route('register') }}" class="link-primary text-decoration-none">Register</a></p>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 ">
            <img src="{{ asset('img/trendzine.jpg') }}" alt="Trendzine" class="card-img-top h-100" >
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
