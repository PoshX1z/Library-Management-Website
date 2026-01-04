<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Library Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
        }
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 10px;
        }
        .btn-primary:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>

    <div class="card login-card p-4">
        <div class="card-body">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-book-reader fa-2x"></i>
                </div>
                <h4 class="fw-bold text-dark">Library Admin</h4>
                <small class="text-muted">เข้าสู่ระบบจัดการห้องสมุด</small>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger small">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

<form action="{{ route('login.perform') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">อีเมล</label>
                    <div class="input-group has-validation"> <span class="input-group-text bg-light border-end-0 @error('email') border-danger text-danger @enderror">
                            <i class="fas fa-envelope {{ $errors->has('email') ? 'text-danger' : 'text-muted' }}"></i>
                        </span>
                        <input type="email" name="email" 
                               class="form-control border-start-0 bg-light @error('email') is-invalid @enderror" 
                               placeholder="admin@library.com" 
                               value="{{ old('email') }}" 
                               required autofocus>
                        
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">รหัสผ่าน</label>
                    <div class="input-group has-validation">
                        <span class="input-group-text bg-light border-end-0 @error('password') border-danger text-danger @enderror">
                            <i class="fas fa-lock {{ $errors->has('password') ? 'text-danger' : 'text-muted' }}"></i>
                        </span>
                        <input type="password" name="password" 
                               class="form-control border-start-0 bg-light @error('password') is-invalid @enderror" 
                               placeholder="••••••••" 
                               required>

                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" checked>
                    <label class="form-check-label small text-muted" for="remember">
                        จดจำฉันไว้ในระบบ (14 วัน)
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold">เข้าสู่ระบบ</button>
                </div>
            </form>
        </div>
        <div class="card-footer bg-white border-0 text-center py-3">
            <small class="text-muted">Protected System &copy; 2026</small>
        </div>
    </div>

</body>
</html>