<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div
        style="min-height: 100vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: var(--space-8);">
        <div>
            <h1
                style="font-size: var(--font-size-5xl); background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: var(--space-3);">
                404</h1>
            <h2 style="color: var(--text-secondary); margin-bottom: var(--space-4);">Page Not Found</h2>
            <p style="color: var(--text-muted); margin-bottom: var(--space-8); max-width: 400px;">The page you're
                looking for doesn't exist or has been moved.</p>
            <div class="btn-group" style="justify-content: center;">
                <a href="/" class="btn btn-primary">Go Home</a>
                <a href="/jobs" class="btn btn-secondary">Browse Jobs</a>
            </div>
        </div>
    </div>
</body>

</html>