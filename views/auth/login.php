<!-- Login Page -->
<div class="auth-page">
    <!-- Left Side: Form -->
    <div class="auth-left">
        <div class="auth-card">
            <div class="auth-header">
                <a href="/" class="logo"><?= APP_NAME ?></a>
                <h2>Welcome Back</h2>
                <p>Sign in to your account to continue</p>
            </div>

            <?php $flash = getFlash(); ?>
            <?php if ($flash): ?>
                <div class="alert alert-<?= h($flash['type']) ?>">
                    <?= h($flash['message']) ?>
                </div>
            <?php endif; ?>

            <?php $errors = getErrors(); ?>
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <div class="alert alert-error">
                        <?= h($error) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

            <form action="/login" method="POST">
                <?= csrfField() ?>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="you@example.com"
                        value="<?= h(old('email')) ?>" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-lg">Sign In</button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="/register">Create one</a>
            </div>

            <div class="auth-footer" style="margin-top: var(--space-3); padding-top: var(--space-3); border-top: 1px solid var(--border-color);">
                <small class="text-muted">Demo accounts:<br>
                Admin: admin@jobsystem.com<br>
                Employer: employer@techcorp.com<br>
                Seeker: seeker@email.com<br>
                Password (all): password</small>
            </div>
        </div>
    </div>

    <!-- Right Side: Image -->
     <div class="auth-right">
        <div class="auth-overlay">
            <h1>JobConnect</h1>
            <p>Connect with top employers and unlock new opportunities.</p>
        </div>
    </div>
</div>