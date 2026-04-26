<h2 class="profile-title">My Profile</h2>

<div class="card profile-card">

    <!-- FULL NAME -->
    <div class="profile-row">
        <div class="profile-label">Name</div>

        <form method="POST" action="/profile/update-field" class="profile-form">
            <?= csrfField() ?>
            <input type="hidden" name="field" value="full_name">

            <span id="full_nameText" class="profile-value">
                <?= h($user['full_name']) ?>
            </span>

            <input type="text"
                name="value"
                id="full_nameInput"
                value="<?= h($user['full_name']) ?>"
                class="form-control is-hidden"
                oninput="toggleSave('full_name')">

            <div class="profile-actions">
                <button type="button"
                        onclick="editField('full_name')"
                        id="full_nameEdit"
                        class="profile-btn edit">
                    Edit
                </button>

                <button type="submit"
                        id="full_nameSave"
                        class="profile-btn save is-hidden">
                    Save
                </button>

                <button type="button"
                        onclick="cancelEdit('full_name')"
                        id="full_nameCancel"
                        class="profile-btn cancel is-hidden">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- EMAIL -->
    <div class="profile-row">
        <div class="profile-label">Email</div>

        <form method="POST" action="/profile/update-field" class="profile-form">
            <?= csrfField() ?>
            <input type="hidden" name="field" value="email">

            <span id="emailText" class="profile-value">
                <?= h($user['email']) ?>
            </span>

            <input type="text"
                name="value"
                id="emailInput"
                value="<?= h($user['email']) ?>"
                class="form-control is-hidden"
                oninput="toggleSave('email')">

            <div class="profile-actions">
                <button type="button"
                        onclick="editField('email')"
                        id="emailEdit"
                        class="profile-btn edit">
                    Edit
                </button>

                <button type="submit"
                        id="emailSave"
                        class="profile-btn save is-hidden">
                    Save
                </button>

                <button type="button"
                        onclick="cancelEdit('email')"
                        id="emailCancel"
                        class="profile-btn cancel is-hidden">
                    Cancel
                </button>
            </div>
        </form>
    </div>

    <!-- COMPANY NAME -->
    <?php if ($user['role'] === 'employer'): ?>
        <div class="profile-row">
            <div class="profile-label">Company</div>

            <form method="POST" action="/profile/update-field" class="profile-form">
                <?= csrfField() ?>
                <input type="hidden" name="field" value="company_name">

                <span id="company_nameText" class="profile-value">
                    <?= h($user['company_name']) ?>
                </span>

                <input type="text"
                    name="value"
                    id="company_nameInput"
                    value="<?= h($user['company_name']) ?>"
                    class="form-control is-hidden"
                    oninput="toggleSave('company_name')">

                <div class="profile-actions">
                    <button type="button"
                            onclick="editField('company_name')"
                            id="company_nameEdit"
                            class="profile-btn edit">
                        Edit
                    </button>

                    <button type="submit"
                            id="company_nameSave"
                            class="profile-btn save is-hidden">
                        Save
                    </button>

                    <button type="button"
                            onclick="cancelEdit('company_name')"
                            id="company_nameCancel"
                            class="profile-btn cancel is-hidden">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- PHONE -->
    <?php if ($user['role'] !== 'admin'): ?>
        <div class="profile-row">
            <div class="profile-label">Phone</div>

            <form method="POST" action="/profile/update-field" class="profile-form">
                <?= csrfField() ?>
                <input type="hidden" name="field" value="phone">

                <span id="phoneText" class="profile-value">
                    <?= h($user['phone']) ?>
                </span>

                <input type="text"
                    name="value"
                    id="phoneInput"
                    value="<?= h($user['phone']) ?>"
                    class="form-control is-hidden"
                    oninput="toggleSave('phone')">

                <div class="profile-actions">
                    <button type="button"
                            onclick="editField('phone')"
                            id="phoneEdit"
                            class="profile-btn edit">
                        Edit
                    </button>

                    <button type="submit"
                            id="phoneSave"
                            class="profile-btn save is-hidden">
                        Save
                    </button>

                    <button type="button"
                            onclick="cancelEdit('phone')"
                            id="phoneCancel"
                            class="profile-btn cancel is-hidden">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

</div>

<!-- PASSWORD -->
<div class="card profile-card profile-password-card">

    <div class="profile-password-header" onclick="togglePassword()">
        <h3 class="profile-subtitle">Change Password</h3>
        <span id="pwArrow" class="pw-arrow">⮟</span>
    </div>

    <div id="passwordForm" class="profile-password-body is-hidden">
        <form method="POST" action="/profile/update-password" class="profile-form">
            <?= csrfField() ?>

            <input type="password" name="current_password" placeholder="Current password" class="form-control">
            <input type="password" name="new_password" placeholder="New password" class="form-control">
            <input type="password" name="confirm_password" placeholder="Confirm new password" class="form-control">

            <button type="submit" class="profile-btn save">
                Update Password
            </button>
        </form>
    </div>
</div>

<?php if (in_array($user['role'], ['employer', 'jobseeker'])): ?>

<div class="profile-deactivate">
    <form id="deactivateForm" method="POST" action="/profile/deactivate" class="hidden-form">
        <?= csrfField() ?>
    </form>

    <button class="btn-deactivate" onclick="confirmDeactivate()">
        Deactivate Account
    </button>
</div>

<?php endif; ?>