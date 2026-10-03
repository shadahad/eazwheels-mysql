<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - EazWheels</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 380px;
        }
        .login-card h2 {
            margin-top: 0;
            text-align: center;
            color: #1f2937;
        }
        .input-group {
            margin-bottom: 1rem;
        }
        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #4b5563;
        }
        .input-group input {
            width: 100%;
            padding: 0.6rem;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
        }
        button:hover {
            background: #1d4ed8;
        }
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        .status-message {
            color: #16a34a;
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Admin Access</h2>

    @if (session('status'))
        <div class="status-message">{{ session('status') }}</div>
    @endif

    @if ($errors->has('admin_key'))
        <div class="error-message">{{ $errors->first('admin_key') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <div class="input-group">
            <label for="admin_key">Secret Admin Key</label>
            <input type="password" id="admin_key" name="admin_key" required autofocus placeholder="Enter your key">
        </div>
        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>