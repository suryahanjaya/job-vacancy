<!-- Admin: Manage Job Postings -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1>Manage Job Postings</h1>
            <p class="text-muted">Review and moderate all job vacancy listings</p>
        </div>
        <a href="/admin/dashboard" class="btn btn-secondary btn-sm">&larr; Back to Dashboard</a>
    </div>

    <?php if (!empty($jobs)): ?>
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Employer</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td class="text-muted">#<?= $job['id'] ?></td>
                                    <td style="font-weight: 600;"><?= h($job['job_title_name']) ?></td>
                                    <td><?= h($job['company_name'] ?? $job['employer_name']) ?></td>
                                    <td><span class="badge badge-primary"><?= h($job['job_category_name']) ?></span></td>
                                    <td><?= h($job['city_name']) ?>, <?= h($job['country_name']) ?></td>
                                    <td><?= h($job['job_level_name']) ?></td>
                                    <td><a href="/admin/jobs/<?= $job['id'] ?>/toggle"
                                            class="badge badge-<?= $job['is_active'] ? 'success' : 'warning' ?>"
                                            style="cursor:pointer;text-decoration:none;"><?= $job['is_active'] ? 'Active' : 'Inactive' ?></a>
                                    </td>
                                    <td class="text-muted"><?= formatDate($job['created_at']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/jobs/<?= $job['id'] ?>" class="btn btn-sm btn-secondary"
                                                target="_blank">View</a>
                                            <form action="/admin/jobs/<?= $job['id'] ?>/delete" method="POST"
                                                style="display:inline;" onsubmit="return confirm('Remove this job posting?');">
                                                <?= csrfField() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php if ($pagination['totalPages'] > 1): ?>
            <div class="pagination">
                <?php if ($pagination['page'] > 1): ?><a
                        href="/admin/jobs?page=<?= $pagination['page'] - 1 ?>">Prev</a><?php endif; ?>
                <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                    <?php if ($i === $pagination['page']): ?><span class="active"><span><?= $i ?></span></span><?php else: ?><a
                            href="/admin/jobs?page=<?= $i ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($pagination['page'] < $pagination['totalPages']): ?><a
                        href="/admin/jobs?page=<?= $pagination['page'] + 1 ?>">Next</a><?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>No job postings</h3>
            <p>No job postings have been created yet.</p>
        </div>
    <?php endif; ?>
</div>