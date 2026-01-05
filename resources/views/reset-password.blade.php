<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - E-Cooking Inventory</title>
    <link rel="icon" type="image/png" href="{{ asset('pictures/creec-logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .reset-card {
            max-width: 380px;
            width: 100%;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .form-control {
            border-radius: 8px;
            padding: 10px 15px;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="reset-card">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <div class="text-center mb-3">
                    <img src="{{ asset('pictures/creec-logo.png') }}" alt="CREEC" height="60" class="mb-2">
                    <h4 class="mb-1"><i class="fas fa-lock me-2"></i>New Password</h4>
                    <p class="text-muted small mb-0">Enter your new password</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        <small>{{ $errors->first() }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="/reset-password">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="mb-3">
                        <label for="password" class="form-label small"><i class="fas fa-key me-1"></i>New Password</label>
                        <input type="password" class="form-control form-control-sm" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label small"><i class="fas fa-key me-1"></i>Confirm Password</label>
                        <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="fas fa-check me-2"></i>Reset Password
                    </button>
                    <div class="text-center">
                        <a href="/login" class="text-muted small"><i class="fas fa-arrow-left me-1"></i>Back to Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
