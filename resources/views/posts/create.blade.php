@extends('../template')
@section('title', 'Create Post')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Post</div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="content">Content</label>
                            <textarea name="content" id="content" rows="10" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                            @error('content')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mt-3">
                            <label for="categories">Categories</label>
                            <select name="categories[]" id="categories" class="form-select" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select2-selection__choice {
        background-color: #007bff !important;
        color: #fff !important;
        border: 1px solid #007bff !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
        color: #fff !important;
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff !important;
        background-color: #0056b3 !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('#categories').select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: 'Select categories',
            closeOnSelect: false,
            allowClear: true
        });
    });
</script>
@endpush