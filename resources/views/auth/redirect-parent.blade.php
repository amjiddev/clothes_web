<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Redirecting...</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            background: #f8f9fa;
        }
        .redirect-message {
            text-align: center;
            padding: 2rem;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #d4af37;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            color: #333;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="redirect-message">
        <div class="spinner"></div>
        <p class="message">Login successful! Redirecting...</p>
    </div>
    
    <script>
        // Redirect parent window (break out of iframe)
        if (window.top !== window.self) {
            // We're in an iframe
            window.top.location.href = '{{ $redirectUrl }}';
        } else {
            // Direct access (not in iframe)
            window.location.href = '{{ $redirectUrl }}';
        }
    </script>
</body>
</html>
