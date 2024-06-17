@extends('../template')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Delete Account') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('account.delete') }}">
                            @csrf
                            @method('DELETE')

                            <div class="alert alert-danger" role="alert">
                                {{ __('Are you sure you want to delete your account? This action cannot be undone.') }}
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-primary" type="button"
                                    onclick="window.history.back();">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    {{ __('Delete Account') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
