<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="<?= h($title ?? 'Find your dream job') ?> - <?= APP_NAME ?> Job Vacancy Management System">
    <title><?= h($title ?? 'Home') ?> | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="preload" as="image" href="/images/auth.jpg">
</head>

<body class="<?= isset($isAuthPage) ? 'auth-layout' : '' ?>">
    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="navbar-inner">
            <a href="/" class="navbar-brand"><?= APP_NAME ?></a>

            <button class="mobile-toggle" id="mobileToggle" onclick="toggleMobileMenu()">Menu</button>

            <ul class="navbar-nav" id="navMenu">
                <li><a href="/" class="<?= ($requestUri ?? '') === '/' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/jobs" class="<?= str_starts_with($requestUri ?? '', '/jobs') ? 'active' : '' ?>">Find
                        Jobs</a></li>

                <?php if (isLoggedIn() && getUserRole() === 'employer'): ?>
                    <li><a href="/employer/jobs"
                            class="<?= str_starts_with($requestUri ?? '', '/employer') ? 'active' : '' ?>">My Postings</a>
                    </li>
                    <li><a href="/dashboard"
                            class="<?= str_starts_with($requestUri ?? '', '/dashboard') ? 'active' : '' ?>">Dashboard</a>
                    </li>
                <?php endif; ?>

                <?php if (isLoggedIn() && getUserRole() === 'admin'): ?>
                    <li><a href="/dashboard"
                            class="<?= str_starts_with($requestUri ?? '', '/dashboard') ? 'active' : '' ?>">Admin</a></li>
                <?php endif; ?>
            </ul>

            <div class="navbar-actions">
                <?php if (isLoggedIn()): ?>
                    <a href="/profile" class="user-menu">
                        <div class="user-avatar"><?= strtoupper(substr(getUserName(), 0, 1)) ?></div>
                        <div class="user-info">
                            <span class="user-name"><?= h(getUserName()) ?></span>
                            <span class="user-role"><?= h(getUserRole()) ?></span>
                        </div>
                    </a>
                    <a href="/logout" class="btn btn-secondary btn-sm">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn btn-secondary btn-sm">Login</a>
                    <a href="/register" class="btn btn-primary btn-sm">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="page-wrapper">
        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="container" style="padding-top: var(--space-5);">
                <div class="alert alert-<?= h($flash['type']) ?>" id="flashAlert">
                    <?= h($flash['message']) ?>
                </div>
            </div>
        <?php endif; ?>

        <?php $errors = getErrors(); ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= h($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-text">&copy; <?= date('Y') ?> <?= APP_NAME ?>. HCMUT Web Programming Assignment.
                </div>
                <div class="footer-text">Built with PHP &amp; MySQL | MVC Architecture</div>
            </div>
        </div>
    </footer>

    <script src="/js/app.js"></script>
</body>

</html>