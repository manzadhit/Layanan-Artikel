@extends('template')

@section('title', 'Edit Profile - TrendZine')

<div class="container-xl px-4 mt-4">
    <div class="row">
        <div class="col-xl-4">
            <!-- Profile picture card-->
            <div class="card mb-4 mb-xl-0">
                <div class="card-header">Profile Picture</div>
                <div class="card-body text-center d-flex flex-column align-items-center">
                    @if ($user->profile_image)
                        <img id="profileImage" src="{{ asset('profile_images/' . $user->profile_image) }}"
                            alt="Profile Picture" class="rounded-circle mb-3"
                            style="width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <div id="profileImage" class="border-0 btn-secondary rounded-circle position-relative mb-3"
                            style="width: 200px; height: 200px; background-color: {{ $user->profile_color }}">
                            <p style="font-size: 8rem"
                                class="display-5 h-100 text-center position-absolute top-50 start-50 translate-middle">
                                {{ $user->name[0] }}
                            </p>
                        </div>
                    @endif

                    <!-- Profile picture help block-->
                    <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>

                    <!-- Profile picture upload form-->
                    <form id="profileImageForm" action="{{ route('profile.uploadImage') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" id="profileImageInput" name="profile_image" class="form-control d-none"
                            accept="image/*">
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" id="updateImageBtn" class="btn btn-primary">Update Image</button>
                            @if ($user->profile_image)
                                <button type="button" id="removeImageBtn" class="btn btn-danger">Remove Image</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <!-- Account details card-->
            <div class="card mb-4">
                <div class="card-header">Account Details</div>
                <div class="card-body">
                    <form action="{{ route('profile.update', $user->username) }}" method="POST">
                        @csrf

                        <!-- Form Group (name)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputName">Name (how your name will appear to other users on
                                the site)</label>
                            <input class="form-control" id="inputName" type="text" name="name"
                                placeholder="Enter your name" value="{{ old('name', $user->name) }}">
                        </div>

                        <!-- Form Group (username)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputUsername">Username</label>
                            <input class="form-control" id="inputUsername" type="text" name="username"
                                placeholder="Enter your username" value="{{ old('username', $user->username) }}"
                                disabled>
                        </div>

                        <!-- Form Group (email address)-->
                        <div class="mb-3">
                            <label class="small mb-1" for="inputEmailAddress">Email address</label>
                            <input class="form-control" id="inputEmailAddress" type="email" name="email"
                                placeholder="Enter your email address" value="{{ old('email', $user->email) }}">
                        </div>

                        <!-- Save changes button-->
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileImageInput = document.getElementById('profileImageInput');
            const updateImageBtn = document.getElementById('updateImageBtn');
            const removeImageBtn = document.getElementById('removeImageBtn');
            const profileImageForm = document.getElementById('profileImageForm');
            const profileImage = document.getElementById('profileImage');

            updateImageBtn.addEventListener('click', function() {
                profileImageInput.click();
            });

            profileImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profileImage.src = e.target.result;
                    }
                    reader.readAsDataURL(this.files[0]);
                    profileImageForm.submit();
                }
            });

            if (removeImageBtn) {
                removeImageBtn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to remove your profile picture?')) {
                        // Add AJAX call to remove the image
                        fetch('{{ route('profile.removeImage') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Failed to remove image. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
                            });
                    }
                });
            }
        });
    </script>
@endpush
