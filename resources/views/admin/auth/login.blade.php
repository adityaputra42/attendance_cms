<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Attendance Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        body { text-align: center; background: #f3f4f6; }
        .auth-container { max-width: 400px; margin: 100px auto; padding: 2rem; background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); text-align: left; }
        h2 { margin-bottom: 0.5rem; color: #111827; }
        p { color: #6b7280; font-size: 0.875rem; margin-bottom: 2rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; color: #374151; }
        input { width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 0.375rem; }
        button { width: 100%; padding: 0.75rem; background: #4f46e5; color: white; border: none; border-radius: 0.375rem; font-weight: 500; cursor: pointer; margin-top: 1rem; }
        button:hover { background: #4338ca; }
        .alert { padding: 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; margin-bottom: 1rem; color: #991b1b; background: #fee2e2; }
    </style>
</head>
<body>
    <div class="auth-container">
        <h2>Welcome Back</h2>
        <p>Sign in to access your dashboard</p>

        @if ($errors->any())
            <div class="alert">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('do_login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Sign In</button>
        </form>
    </div>
</body>
</html>
