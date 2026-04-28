<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eBarangay Admin</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: rgba(255, 255, 255, 0.65);
            --glass-border: rgba(255, 255, 255, 0.8);
            --glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            
            --text-main: #1e293b;
            --text-light: #475569;
            
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --danger: #dc2626;
            --success: #16a34a;
            
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            background-color: #f1f5f9; /* Very light slate */
            color: var(--text-main);
            overflow-x: hidden;
            min-height: 100vh;
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

        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
        }

        .sidebar {
            width: 260px;
            position: fixed;
            height: 100vh;
            padding-top: 40px;
            z-index: 100;
            border-right: 1px solid var(--glass-border);
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .sidebar h3 {
            color: #1e293b;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 22px;
            text-transform: uppercase;
        }

        .sidebar a {
            display: block;
            color: var(--text-light);
            padding: 16px 32px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
            margin-bottom: 8px;
        }

        .sidebar a:hover {
            background-color: rgba(0, 0, 0, 0.04);
            color: var(--primary);
            border-left: 3px solid var(--primary);
        }

        .main {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
            position: relative;
            z-index: 10;
        }
        
        h2 {
            margin-top: 0;
            font-size: 28px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 32px;
            letter-spacing: -0.5px;
        }

        h3 {
            font-weight: 600;
            color: #1e293b !important;
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 32px;
            border-radius: var(--radius-xl);
            box-shadow: var(--glass-shadow);
            border: 1px solid var(--glass-border);
            margin-bottom: 24px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: rgba(255, 255, 255, 0.4);
            border-radius: var(--radius-lg);
            overflow: hidden;
            border: 1px solid var(--glass-border);
        }

        table th {
            background-color: rgba(255, 255, 255, 0.6);
            color: var(--text-light);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 18px 24px;
            text-align: left;
            border-bottom: 1px solid var(--glass-border);
        }

        table td {
            padding: 18px 24px;
            border-bottom: 1px solid var(--glass-border);
            color: var(--text-main);
            font-size: 14px;
            vertical-align: middle;
            font-weight: 500;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr {
            transition: background 0.2s ease;
        }

        table tr:hover td {
            background-color: rgba(255, 255, 255, 0.8);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea,
        select {
            width: 100%;
            padding: 14px 20px;
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: #1e293b !important;
            background-color: rgba(255, 255, 255, 0.7) !important;
            transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        }
        
        input::placeholder,
        textarea::placeholder {
            color: var(--text-light) !important;
        }

        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--primary);
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        select option {
            background-color: #ffffff;
            color: #1e293b;
        }

        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        hr {
            border: none;
            height: 1px;
            background-color: var(--glass-border);
            margin: 30px 0;
        }

        label {
            color: var(--text-light) !important;
        }

        /* Animated Logout Modal Styles */
        .logout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .logout-modal {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            padding: 32px;
            border-radius: var(--radius-xl);
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 40px rgba(31, 38, 135, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .sand-grain-overlay {
            position: absolute;
            inset: -50%;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0;
            pointer-events: none;
            mix-blend-mode: overlay;
        }

        /* Sand Blur to Clear Animation */
        @keyframes sandBlurToClear {
            0% {
                opacity: 0;
                transform: scale(0.85) translateY(20px);
                filter: blur(12px) contrast(1.5);
            }
            30% {
                opacity: 1;
                filter: blur(6px) contrast(1.2);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
                filter: blur(0px) contrast(1);
            }
        }

        @keyframes sandNoiseFade {
            0% { opacity: 0.6; }
            40% { opacity: 0.3; }
            100% { opacity: 0; }
        }

        .logout-overlay.active {
            display: flex;
            opacity: 1;
        }

        .logout-overlay.active .logout-modal {
            animation: sandBlurToClear 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .logout-overlay.active .sand-grain-overlay {
            animation: sandNoiseFade 0.9s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .logout-modal h3 {
            margin-top: 0;
            font-size: 22px;
            color: var(--text-main) !important;
            margin-bottom: 12px;
        }

        .logout-modal p {
            color: var(--text-light);
            font-size: 15px;
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .logout-actions {
            display: flex;
            gap: 12px;
        }

        .logout-actions .btn-cancel {
            background: rgba(15, 23, 42, 0.05);
            color: var(--text-main);
            flex: 1;
            box-shadow: none;
        }
        .logout-actions .btn-cancel:hover {
            background: rgba(15, 23, 42, 0.1);
            transform: translateY(0);
        }

        .logout-actions .btn-confirm {
            flex: 1;
            background: var(--danger);
            color: white;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        }
        .logout-actions .btn-confirm:hover {
            background: #b91c1c;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(220, 38, 38, 0.4);
        }
    </style>
</head>
<body>

    <!-- Watermark Logo -->
    <div class="bg-logo"></div>

    <div class="sidebar">
        <h3>eBarangay</h3>
        <a href="/admin/dashboard">Dashboard</a>
        <a href="/admin/complaints">Complaints</a>
        <a href="/admin/announcements">Announcements</a>
        <a href="#" onclick="showLogoutModal(event)">Logout</a>
    </div>

    <div class="main">
        @yield('content')
    </div>

    <!-- Animated Sand Blur Logout Modal -->
    <div id="logoutModalOverlay" class="logout-overlay">
        <div class="logout-modal">
            <!-- This element provides the sand/grain texture overlay -->
            <div class="sand-grain-overlay"></div>
            
            <h3>Confirm Logout</h3>
            <p>Are you sure you want to securely log out of the admin portal?</p>
            
            <div class="logout-actions">
                <button onclick="closeLogoutModal()" class="btn-cancel">Cancel</button>
                <a href="/admin/logout" class="btn-confirm">Yes, Logout</a>
            </div>
        </div>
    </div>

    <script>
        function showLogoutModal(e) {
            e.preventDefault();
            const overlay = document.getElementById('logoutModalOverlay');
            overlay.style.display = 'flex';
            
            // Force a small reflow before adding the active class to trigger the CSS animation
            void overlay.offsetWidth;
            
            overlay.classList.add('active');
        }

        function closeLogoutModal() {
            const overlay = document.getElementById('logoutModalOverlay');
            overlay.classList.remove('active');
            
            // Wait for the opacity fade transition to finish before hiding display
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
    </script>
</body>
</html>