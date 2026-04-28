<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - eBarangay</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: #f1f5f9; /* Light slate */
            color: #1e293b;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
        }

        /* Watermark Logo Background */
        .bg-logo {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70vh;
            height: 70vh;
            max-width: 80vw;
            background-image: url('/images/umingan-logo.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.12; /* Subtle watermark effect */
            z-index: -1;
            pointer-events: none; /* Prevent interacting with it */
        }

        .login-card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 48px 40px;
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-sizing: border-box;
            z-index: 10;
        }
        .login-card h2 {
            margin-top: 0;
            font-size: 28px;
            font-weight: 800;
            text-align: center;
            color: #1e293b;
            margin-bottom: 32px;
            letter-spacing: -0.5px;
        }
        .error-msg {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            margin-bottom: 24px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .form-group {
            margin-bottom: 24px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            box-sizing: border-box;
            padding: 14px 20px;
            border: 1px solid rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: #1e293b;
            background-color: rgba(255, 255, 255, 0.7);
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }
        button {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        button:hover {
            background-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        }
        button:active {
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- Watermark Logo -->
    <div class="bg-logo"></div>

    <div class="login-card">
        <h2>Admin Login</h2>

        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form method="POST" action="/admin/login">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>