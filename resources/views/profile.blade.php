@php
    $user = auth()->user() ?? auth('trainer')->user() ?? auth('technician')->user();
    $userType = auth('trainer')->check() ? 'trainer' : (auth('technician')->check() ? 'technician' : 'user');
    $badgeClass = $userType === 'trainer' ? 'bg-danger' : ($userType === 'technician' ? 'bg-info' : 'bg-primary');
    $headerColor = $userType === 'trainer' ? 'bg-danger' : ($userType === 'technician' ? 'bg-info' : 'bg-primary');
    $attributes = $user ? $user->getAttributes() : [];
    $fillable = $user ? $user->getFillable() : [];
    $excludeFields = ['id', 'password', 'remember_token', 'email_verified_at', 'created_at', 'updated_at', 'deleted_at', '_token', 'profile_picture', 'image', 'photo'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - E-Cooking Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, {{ $userType === 'trainer' ? '#667eea 0%, #764ba2' : ($userType === 'technician' ? '#4facfe 0%, #00f2fe' : '#667eea 0%, #764ba2') }} 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
            padding-top: 80px;
            padding-bottom: 40px;
        }
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: none;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%);">
        <div class="container-fluid">
            <a href="https://creec.or.ug" target="_blank">
                <img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC" height="35">
            </a>
            <span class="navbar-text text-white">
                <i class="fas fa-user-edit"></i> My Profile
            </span>
            <div>
                @if($userType === 'trainer')
                    <a href="/trainer/panel" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                @elseif($userType === 'technician')
                    <a href="/technician/panel" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                @endif
                <form action="/logout" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Profile Header Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header {{ $headerColor }} text-white text-center py-3">
                            <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Update My Profile</h4>
                        </div>
                        <div class="card-body text-center p-5">
                            <form action="/profile/upload-picture" method="POST" enctype="multipart/form-data" id="uploadForm">
                                @csrf
                                <div class="position-relative d-inline-block mb-3">
                                    @if($user && $user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" id="profileImg">
                                    @else
                                        <div class="rounded-circle {{ $badgeClass }} text-white d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem;" id="profilePlaceholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <label for="profileInput" class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle" style="width: 35px; height: 35px; padding: 0; cursor: pointer;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="profile_picture" id="profileInput" class="d-none" accept="image/*" onchange="previewAndUpload(this)">
                                </div>
                            </form>
                            <h2 class="mb-2">{{ $user->name ?? 'User' }}</h2>
                            <p class="text-muted mb-4">
                                <span class="badge {{ $badgeClass }} fs-6">{{ ucfirst($userType) }}</span>
                            </p>
                            <p class="mb-1"><i class="fas fa-envelope text-muted me-2"></i>{{ $user->email ?? 'N/A' }}</p>
                            @if($user->phone ?? false)
                            <p class="mb-0"><i class="fas fa-phone text-muted me-2"></i>{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Edit Profile Form -->
                    <div class="card shadow">
                        <div class="card-header {{ $headerColor }} text-white">
                            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Profile Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="/profile/update" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Basic Information -->
                                    <div class="col-12 mb-3">
                                        <h6 class="text-primary border-bottom pb-2"><i class="fas fa-user me-2"></i>Basic Information</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-user me-2 text-muted"></i>Full Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-envelope me-2 text-muted"></i>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-phone me-2 text-muted"></i>Phone Number</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $user->phone ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-map-marker-alt me-2 text-muted"></i>Location</label>
                                        <input type="text" name="location" class="form-control" value="{{ $user->location ?? '' }}">
                                    </div>

                                    <!-- Professional Information -->
                                    <div class="col-12 mb-3 mt-3">
                                        <h6 class="text-success border-bottom pb-2"><i class="fas fa-briefcase me-2"></i>Professional Information</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-star me-2 text-muted"></i>Specialty</label>
                                        <input type="text" name="specialty" class="form-control" value="{{ $user->specialty ?? '' }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-briefcase me-2 text-muted"></i>Years of Experience</label>
                                        <input type="number" name="experience" class="form-control" value="{{ $user->experience ?? '' }}" min="0">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-graduation-cap me-2 text-muted"></i>Qualifications</label>
                                        <textarea name="qualifications" class="form-control" rows="3">{{ $user->qualifications ?? '' }}</textarea>
                                    </div>

                                    <!-- Rating System -->
                                    <div class="col-12 mb-3 mt-3">
                                        <h6 class="text-warning border-bottom pb-2"><i class="fas fa-star me-2"></i>Performance Rating</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-star me-2 text-muted"></i>Overall Rating</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="star-rating" data-rating="{{ $user->rating ?? 0 }}">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star star" data-value="{{ $i }}" style="font-size: 1.5rem; cursor: pointer; color: {{ ($user->rating ?? 0) >= $i ? '#ffc107' : '#ddd' }};"></i>
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" id="ratingInput" value="{{ $user->rating ?? 0 }}">
                                            <span class="badge bg-warning text-dark" id="ratingDisplay">{{ $user->rating ?? 0 }}/5</span>
                                        </div>
                                    </div>

                                    <!-- Additional Fields -->
                                    @foreach($fillable as $field)
                                        @if(!in_array($field, ['name', 'email', 'phone', 'location', 'specialty', 'experience', 'qualifications', 'rating']) && !in_array($field, $excludeFields))
                                            @php
                                                $value = $attributes[$field] ?? '';
                                                $isJson = is_array($value) || (is_string($value) && json_decode($value, true));
                                            @endphp
                                            @if(!$isJson)
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">
                                                    <i class="fas fa-edit me-2 text-muted"></i>
                                                    {{ ucwords(str_replace('_', ' ', $field)) }}
                                                </label>
                                                @if(in_array($field, ['description', 'bio', 'notes', 'comments']))
                                                    <textarea name="{{ $field }}" class="form-control" rows="3">{{ $value }}</textarea>
                                                @else
                                                    <input type="text" name="{{ $field }}" class="form-control" value="{{ $value }}">
                                                @endif
                                            </div>
                                            @endif
                                        @endif
                                    @endforeach

                                    <!-- Change Password Section -->
                                    <div class="col-12 mb-3 mt-4">
                                        <h6 class="text-danger border-bottom pb-2"><i class="fas fa-lock me-2"></i>Change Password (Optional)</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-key me-2 text-muted"></i>New Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current password">
                                        <small class="text-muted">Minimum 6 characters</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold"><i class="fas fa-key me-2 text-muted"></i>Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm your new password">
                                    </div>
                                </div>
                                <div class="text-center mt-4 pt-3 border-top">
                                    <button type="submit" class="btn {{ $headerColor }} text-white px-5 py-3 btn-lg">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function previewAndUpload(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('profileImg');
                    const placeholder = document.getElementById('profilePlaceholder');
                    if (img) {
                        img.src = e.target.result;
                    } else if (placeholder) {
                        placeholder.outerHTML = '<img src="' + e.target.result + '" alt="Profile" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" id="profileImg">';
                    }
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('uploadForm').submit();
            }
        }

        // Star Rating System
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star-rating .star');
            const ratingInput = document.getElementById('ratingInput');
            const ratingDisplay = document.getElementById('ratingDisplay');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = value;
                    ratingDisplay.textContent = value + '/5';
                    
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        s.style.color = starValue <= value ? '#ffc107' : '#ddd';
                    });
                });

                star.addEventListener('mouseenter', function() {
                    const value = parseInt(this.getAttribute('data-value'));
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        s.style.color = starValue <= value ? '#ffc107' : '#ddd';
                    });
                });
            });

            const starRating = document.querySelector('.star-rating');
            if (starRating) {
                starRating.addEventListener('mouseleave', function() {
                    const currentRating = parseInt(ratingInput.value) || 0;
                    stars.forEach(s => {
                        const starValue = parseInt(s.getAttribute('data-value'));
                        s.style.color = starValue <= currentRating ? '#ffc107' : '#ddd';
                    });
                });
            }
        });
    </script>
</body>
</html>
