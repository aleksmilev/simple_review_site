<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top left, #1e3a8a, #020617);
            color: #e5e7eb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .wrapper {
            text-align: center;
            padding: 3rem 2rem;
            background: rgba(15, 23, 42, 0.9);
            border-radius: 1rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            max-width: 480px;
            width: 90%;
        }
        .code {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            color: #60a5fa;
        }
        .title {
            margin-top: 0.5rem;
            font-size: 1.75rem;
            font-weight: 700;
        }
        .message {
            margin-top: 1rem;
            font-size: 0.95rem;
            color: #9ca3af;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            margin-top: 1.75rem;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #e5e7eb;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
        }
        .button:hover {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="code">404</div>
        <div class="title">Page not found</div>
        <p class="message">
            The page you are looking for doesn&apos;t exist or may have been moved.
        </p>
    </div>
</body>
</html>

