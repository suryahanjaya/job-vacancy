<!-- Register Page -->
<div class="auth-page">
    <div class="auth-card" style="max-width: 520px;">
        <div class="auth-header">
            <a href="/" class="logo"><?= APP_NAME ?></a>
            <h2>Create Account</h2>
            <p>Join our platform to find or post jobs</p>
        </div>

        <?php $flash = getFlash(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
        <?php endif; ?>

        <?php $errors = getErrors(); ?>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <div><?= h($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST">
            <?= csrfField() ?>

            <div class="form-group">
                <label class="form-label">I want to <span class="required">*</span></label>
                <div class="role-selector">
                    <div class="role-option">
                        <input type="radio" name="role" id="role_jobseeker" value="jobseeker"
                               <?= old('role', 'jobseeker') === 'jobseeker' ? 'checked' : '' ?>>
                        <label for="role_jobseeker">
                            <span class="role-title">Find Jobs</span>
                            <span class="role-desc">Job Seeker</span>
                        </label>
                    </div>
                    <div class="role-option">
                        <input type="radio" name="role" id="role_employer" value="employer"
                               <?= old('role') === 'employer' ? 'checked' : '' ?>>
                        <label for="role_employer">
                            <span class="role-title">Post Jobs</span>
                            <span class="role-desc">Employer</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" name="full_name" class="form-control" placeholder="Your full name"
                       value="<?= h(old('full_name')) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email Address <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="you@example.com"
                       value="<?= h(old('email')) ?>" required>
            </div>

            <div class="form-group" id="companyField" style="<?= old('role') !== 'employer' ? 'display:none' : '' ?>">
                <label class="form-label">Company Name <span class="required">*</span></label>
                <input type="text" name="company_name" class="form-control" placeholder="Your company name"
                       value="<?= h(old('company_name')) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Phone (optional)</label>
                <input type="text" name="phone" class="form-control" placeholder="Phone number"
                       value="<?= h(old('phone')) ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Password <span class="required">*</span></label>
                <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required>
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password <span class="required">*</span></label>
                <input type="password" name="password_confirm" class="form-control" placeholder="Repeat your password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Create Account</button>
        </form>

        <div class="auth-footer">
            Already have an account? <a href="/login">Sign in</a>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('input[name="role"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('companyField').style.display =
            this.value === 'employer' ? '' : 'none';
    });
});
</script>
