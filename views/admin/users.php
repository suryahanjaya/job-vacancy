<!-- Admin: Manage Users -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1>Manage Users</h1>
            <p class="text-muted">View and manage all registered users</p>
        </div>
        <a href="/admin/dashboard" class="btn btn-secondary btn-sm">&larr; Back to Dashboard</a>
    </div>

    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td class="text-muted">#<?= $user['id'] ?></td>
                                <td style="font-weight: 600;">
                                    <div class="d-flex align-center gap-2">
                                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 12px;">
                                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?></div>
                                        <?= h($user['full_name']) ?>
                                    </div>
                                </td>
                                <td><?= h($user['email']) ?></td>
                                <td><span
                                        class="badge badge-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'employer' ? 'info' : 'success') ?>"><?= ucfirst(h($user['role'])) ?></span>
                                </td>
                                <td><?= h($user['company_name'] ?? '—') ?></td>
                                <td><span
                                        class="badge badge-<?= $user['is_active'] ? 'success' : 'warning' ?>"><?= $user['is_active'] ? 'Active' : 'Inactive' ?></span>
                                </td>
                                <td class="text-muted"><?= formatDate($user['created_at']) ?></td>
                                <td>
                                    <?php if ($user['id'] != getUserId()): ?>
                                        <a href="/admin/users/<?= $user['id'] ?>/toggle"
                                            class="btn btn-sm <?= $user['is_active'] ? 'btn-warning' : 'btn-success' ?>"><?= $user['is_active'] ? 'Deactivate' : 'Activate' ?></a>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: var(--font-size-xs);">Current user</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>