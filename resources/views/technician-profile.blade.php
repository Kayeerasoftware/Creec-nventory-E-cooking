<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - Technician</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px;">
    <div class="container" style="max-width: 800px;">
        <div class="card shadow-lg">
            <div class="card-header text-white" style="background: linear-gradient(90deg, #140168 0%, #5039d6 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-user-edit me-2"></i>Update My Profile</h4>
                    <a href="/technician/home" class="btn btn-sm btn-light"><i class="fas fa-arrow-left me-1"></i>Back</a>
                </div>
            </div>
            <div class="card-body p-4">
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Profile Photo -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            <img id="profilePreview" src="{{ auth()->user()->image ? asset('storage/' . auth()->user()->image) : asset('pictures/default-avatar.png') }}" 
                                 class="rounded-circle border border-3 border-primary" style="width: 150px; height: 150px; object-fit: cover;">
                            <label for="profilePhoto" class="position-absolute bottom-0 end-0 btn btn-primary btn-sm rounded-circle" style="width: 40px; height: 40px; cursor: pointer;">
                                <i class="fas fa-camera"></i>
                            </label>
                            <input type="file" id="profilePhoto" name="profile_photo" accept="image/*" class="d-none">
                        </div>
                        <p class="text-muted small mt-2">Click camera icon to change photo</p>
                    </div>

                    <!-- Basic Info -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-user me-1"></i>Name</label>
                            <input type="text" class="form-control" name="name" value="{{ auth()->user()->name ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-envelope me-1"></i>Email</label>
                            <input type="email" class="form-control" name="email" value="{{ auth()->user()->email ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-phone me-1"></i>Phone</label>
                            <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i>Location</label>
                            <input type="text" class="form-control" name="location" value="{{ auth()->user()->location ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-users me-1"></i>Cohort Number</label>
                            <input type="text" class="form-control" name="cohort_number" value="{{ auth()->user()->cohort_number ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-briefcase me-1"></i>Place of Work</label>
                            <input type="text" class="form-control" name="place_of_work" value="{{ auth()->user()->place_of_work ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-building me-1"></i>Training Venue</label>
                            <input type="text" class="form-control" name="venue" value="{{ auth()->user()->venue ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="fas fa-calendar me-1"></i>Training Dates</label>
                            <input type="text" class="form-control" name="training_dates" value="{{ auth()->user()->training_dates ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-birthday-cake me-1"></i>Age</label>
                            <input type="number" class="form-control" name="age" value="{{ auth()->user()->age ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-flag me-1"></i>Nationality</label>
                            <input type="text" class="form-control" name="nationality" value="{{ auth()->user()->nationality ?? '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label"><i class="fas fa-tools me-1"></i>Specialty</label>
                            <input type="text" class="form-control" name="specialty" value="{{ auth()->user()->specialty ?? '' }}">
                        </div>
                    </div>

                    <!-- Change Password -->
                    <hr class="my-4">
                    <h6 class="mb-3"><i class="fas fa-lock me-2"></i>Change Password (Optional)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Image preview
        document.getElementById('profilePhoto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('/technician/profile/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Profile updated successfully!');
                    window.location.href = '/technician/home';
                } else {
                    alert('Error: ' + (data.message || 'Failed to update profile'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating profile');
            });
        });
    </script>
</body>
</html>
