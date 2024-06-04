<!-- create.blade.php -->
@extends('../template')
@section('title', 'Create Post - TrendZine')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">Create Post</div>
                    <div class="card-body">
                        <form id="postForm" action="{{ route('posts.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    required>
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="editor" class="form-label">Content</label>
                                <div id="toolbar-container" class="mb-2"></div>
                                <textarea name="content" id="editor" class="form-control"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror" required>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="categories" class="form-label">Categories</label>
                                <select name="categories[]" id="categories" class="form-select" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="submit-button">Create Post</button>
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
        .ck-editor__editable {
            min-height: 300px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor.create(document.getElementById("editor"), {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'fontSize', 'fontFamily', 'fontColor', 'highlight', '|',
                    'alignment', '|',
                    'numberedList', 'bulletedList', '|',
                    'indent', 'outdent', '|',
                    'link', 'imageUpload', '|',
                    'undo', 'redo'
                ]
            },
            language: 'en',
            image: {
                toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:full', 'imageStyle:alignRight'],
                upload: {
                    types: ['jpeg', 'png', 'gif'],
                    url: '{{ route("ckeditor.upload") }}', // Rute untuk unggah gambar
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            },
            licenseKey: '',
        }).then(editor => {
            window.editor = editor;
        }).catch(error => {
            console.error('Oops, something went wrong!');
            console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
            console.warn('Build id: a7mi1ajjg7k-sym3euhh4em2');
            console.error(error);
        });
    </script>
@endpush
