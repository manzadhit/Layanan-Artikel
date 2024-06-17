@extends('template')

@section('title', 'Create Post - TrendZine')

@section('styles')
    <style>
        #container {
            width: 1000px;
            margin: 20px auto;
        }

        .ck-editor__editable[role="textbox"] {
            min-height: 200px;
        }

        .ck-content .image {
            max-width: 80%;
            margin: 20px auto;
        }

        #title {
            width: 100%;
            padding: 10px;
            font-size: 2em;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-weight: bold;
        }

        .ck-content p,
        .ck-content li {
            font-size: 20px;
        }
    </style>
@endsection

<?php
if (isset($post)) {
    $title = old('title', $post->title);
    $content = old('content', $post->content);
    $route = route('posts.update', $post->slug);
    $method = 'PUT';
} else {
    $title = old('title', '');
    $content = old('content', '');
    $route = route('posts.store');
    $method = 'POST';
}
?>

@section('content')
    <h2 class="text-center fw-bold">{{ isset($post) ? 'Edit Post' : 'Create Post' }}</h2>
    <div id="container">
        <form action="{{ $route }}" method="POST" id="post-form">
            @csrf
            @if (isset($post))
                @method('PUT')
            @endif
            <input class="border" type="text" id="title" name="title" value="{{ $title }}"
                placeholder="Enter your post title here..." />
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <textarea id="editor" name="content">{{ $content }}</textarea>
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <label for="categories">Select Categories:</label>
            <select name="categories[]" class="form-control" id="categories" multiple data-max-selected="3">
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @if (in_array($category->id, old('categories', isset($post) ? $post->categories->pluck('id')->toArray() : []))) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('categories')
                <div class="text-danger">{{ $message }}</div>
            @enderror

            <button class="btn btn-primary mt-3" type="submit">{{ isset($post) ? 'Update' : 'Submit' }}</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        var categories = @json(
            $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'text' => $category->name,
                ];
            }));

        $('#categories').select2({
            data: categories,
            placeholder: 'Select categories',
            maximumSelectionLength: 3
        });

        document.addEventListener('DOMContentLoaded', function() {
            let isFormChanged = false;

            const editor = CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
                toolbar: {
                    items: [
                        'undo', 'redo', '|',
                        'heading', '|', 'bold', 'italic', 'strikethrough', 'underline', 'alignment',
                        '|',
                        'indent', 'outdent', '|', 'link',
                        'uploadImage'
                    ],
                    shouldNotGroupWhenFull: true
                },
                simpleUpload: {
                    uploadUrl: '{{ route('posts.upload-image') }}',
                    withCredentials: true,
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                list: {
                    properties: {
                        styles: true,
                        startIndex: true,
                        reversed: true
                    }
                },
                heading: {
                    options: [{
                            model: 'paragraph',
                            title: 'Paragraph',
                            class: 'ck-heading_paragraph'
                        },
                        {
                            model: 'heading2',
                            view: 'h2',
                            title: 'Heading 2',
                            class: 'ck-heading_heading2'
                        },
                        {
                            model: 'heading3',
                            view: 'h3',
                            title: 'Heading 3',
                            class: 'ck-heading_heading3'
                        },
                        {
                            model: 'heading4',
                            view: 'h4',
                            title: 'Heading 4',
                            class: 'ck-heading_heading4'
                        },
                        {
                            model: 'heading5',
                            view: 'h5',
                            title: 'Heading 5',
                            class: 'ck-heading_heading5'
                        },
                        {
                            model: 'heading6',
                            view: 'h6',
                            title: 'Heading 6',
                            class: 'ck-heading_heading6'
                        }
                    ]
                },
                placeholder: 'write content...',
                fontFamily: {
                    options: [
                        'default',
                        'Arial, Helvetica, sans-serif',
                        'Courier New, Courier, monospace',
                        'Georgia, serif',
                        'Lucida Sans Unicode, Lucida Grande, sans-serif',
                        'Tahoma, Geneva, sans-serif',
                        'Times New Roman, Times, serif',
                        'Trebuchet MS, Helvetica, sans-serif',
                        'Verdana, Geneva, sans-serif'
                    ],
                    supportAllValues: true
                },
                fontSize: {
                    options: [10, 12, 14, 16, 18, 20, 'default', 22],
                    supportAllValues: true,
                    default: 20
                },
                htmlSupport: {
                    allow: [{
                        name: /.*/,
                        attributes: true,
                        classes: true,
                        styles: true
                    }]
                },
                htmlEmbed: {
                    showPreviews: true
                },
                link: {
                    // Automatically add target="_blank" and rel="noopener noreferrer" to all external links.
                    addTargetToExternalLinks: true,

                    // Let the users control the "download" attribute of each link.
                    decorators: [{
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'download'
                        }
                    }]
                },
                removePlugins: [
                    'AIAssistant',
                    'CKBox',
                    'CKFinder',
                    'EasyImage',
                    'MultiLevelList',
                    'RealTimeCollaborativeComments',
                    'RealTimeCollaborativeTrackChanges',
                    'RealTimeCollaborativeRevisionHistory',
                    'PresenceList',
                    'Comments',
                    'TrackChanges',
                    'TrackChangesData',
                    'RevisionHistory',
                    'Pagination',
                    'WProofreader',
                    'MathType',
                    'SlashCommand',
                    'Template',
                    'DocumentOutline',
                    'FormatPainter',
                    'TableOfContents',
                    'PasteFromOfficeEnhanced',
                    'CaseChange'
                ],
                pasteFilter: (value) => {
                    return value.replace(/(<[a-zA-Z0-9\-]+)(\s+style="[^"]*")([^>]*>)/gi, '$1$3');
                }
            }).then(editor => {
                // Fungsi untuk menyimpan data ke localStorage
                function saveToLocalStorage() {
                    const formData = {
                        title: document.getElementById('title').value,
                        content: editor.getData(),
                        categories: Array.from(document.getElementById('categories').selectedOptions)
                            .map(option => option.value)
                    };
                    localStorage.setItem('editPostData', JSON.stringify(formData));
                    isFormChanged = true;
                }

                // Fungsi untuk memuat data dari localStorage
                function loadFromLocalStorage() {
                    const savedData = localStorage.getItem('editPostData');
                    if (savedData) {
                        const formData = JSON.parse(savedData);
                        document.getElementById('title').value = formData.title;
                        editor.setData(formData.content);
                        formData.categories.forEach(categoryId => {
                            document.querySelector(`#categories option[value="${categoryId}"]`)
                                .selected = true;
                        });
                        $('#categories').trigger('change');
                    }
                }

                // Memuat data saat halaman dimuat
                loadFromLocalStorage();

                // Event listeners untuk menyimpan data
                document.getElementById('title').addEventListener('input', saveToLocalStorage);
                editor.model.document.on('change:data', saveToLocalStorage);
                document.getElementById('categories').addEventListener('change', saveToLocalStorage);

                // Event listener untuk mencegah submit form saat Enter ditekan di input title
                document.getElementById('title').addEventListener('keydown', function(event) {
                    if (event.keyCode === 13) { // 13 adalah kode untuk tombol Enter
                        event.preventDefault(); // Mencegah default behavior (submit form)
                    }
                });

                // Hapus localStorage saat form disubmit
                document.getElementById('post-form').addEventListener('submit', function() {
                    localStorage.removeItem('editPostData');
                    isFormChanged = false;
                });

                // Fungsi untuk menghapus localStorage
                function clearLocalStorage() {
                    localStorage.removeItem('editPostData');
                    isFormChanged = false;
                }

                // Fungsi untuk menanyakan pengguna sebelum meninggalkan halaman
                function confirmExit(e) {
                    if (isFormChanged) {
                        const confirmationMessage =
                            'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?';
                        e.returnValue = confirmationMessage;
                        return confirmationMessage;
                    }
                }

                // Event listener untuk konfirmasi saat meninggalkan halaman
                window.addEventListener('beforeunload', function(e) {
                    const message = confirmExit(e);
                    if (message) {
                        // Kita tidak bisa mengetahui pilihan pengguna di sini,
                        // jadi kita akan menghapus localStorage di event 'unload'
                    } else {
                        clearLocalStorage();
                    }
                });

                // Event listener untuk menghapus localStorage saat benar-benar meninggalkan halaman
                window.addEventListener('unload', function() {
                    if (isFormChanged) {
                        clearLocalStorage();
                    }
                });

                // Fungsi untuk menangani navigasi
                function handleNavigation(e) {
                    if (isFormChanged) {
                        const confirmLeave = confirm(
                            'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?'
                        );
                        if (confirmLeave) {
                            clearLocalStorage();
                        } else {
                            e.preventDefault();
                        }
                    }
                }

                // Tambahkan event listener untuk semua link di halaman
                document.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', handleNavigation);
                });

                // Tambahkan event listener untuk tombol back/forward browser
                window.addEventListener('popstate', function(e) {
                    if (isFormChanged) {
                        const confirmLeave = confirm(
                            'Anda memiliki perubahan yang belum disimpan. Apakah Anda yakin ingin meninggalkan halaman ini?'
                        );
                        if (confirmLeave) {
                            clearLocalStorage();
                        } else {
                            history.pushState(null, '', window.location.href);
                        }
                    }
                });

                // Tambahkan event listener untuk tombol back/forward browser
                window.addEventListener('popstate', function(e) {
                    if (isFormChanged) {
                        const confirmLeave = confirm(
                            'back'
                        );
                        if (confirmLeave) {
                            clearLocalStorage();
                        } else {
                            history.pushState(null, '', window.location.href);
                        }
                    }
                });
            });
        });
    </script>
@endpush
