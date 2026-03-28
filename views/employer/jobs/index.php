<!-- Employer: My Job Postings List -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="d-flex justify-between align-center mb-6" style="flex-wrap: wrap; gap: var(--space-4);">
        <div>
            <h1>My Job Postings</h1>
            <p class="text-muted">Manage all your job vacancy listings</p>
        </div>
        <a href="/employer/jobs/create" class="btn btn-primary">Create New Posting</a>
    </div>

    <?php if (!empty($jobs)): ?>
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div class="table-responsive">
                    <table class="table" id="jobsTable">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Category</th>
                                <th>Level</th>
                                <th>Location</th>
                                <th>Salary</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($jobs as $job): ?>
                                <tr>
                                    <td><a href="/employer/jobs/<?= $job['id'] ?>"
                                            style="font-weight: 600; color: var(--text-primary);"><?= h($job['job_title_name']) ?></a>
                                    </td>
                                    <td><span class="badge badge-primary"><?= h($job['job_category_name']) ?></span></td>
                                    <td><?= h($job['job_level_name']) ?></td>
                                    <td><?= h($job['city_name']) ?>, <?= h($job['country_name']) ?></td>
                                    <td class="text-success" style="font-weight: 600;"><?= h($job['salary_range_label']) ?></td>
                                    <td><span class="badge badge-info"><?= h($job['employment_type_name']) ?></span></td>
                                    <td>
                                        <a href="/employer/jobs/<?= $job['id'] ?>/toggle"
                                            class="badge badge-<?= $job['is_active'] ? 'success' : 'warning' ?>"
                                            style="cursor: pointer; text-decoration: none;">
                                            <?= $job['is_active'] ? 'Active' : 'Inactive' ?>
                                        </a>
                                    </td>
                                    <td class="text-muted"><?= timeAgo($job['created_at']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="/employer/jobs/<?= $job['id'] ?>/edit"
                                                class="btn btn-sm btn-secondary">Edit</a>
                                            <form action="/employer/jobs/<?= $job['id'] ?>/delete" method="POST"
                                                style="display:inline;"
                                                onsubmit="return confirm('Are you sure you want to delete this job posting?');">
                                                <?= csrfField() ?>
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
                <?php if ($pagination['page'] > 1): ?>
                    <a href="/employer/jobs?page=<?= $pagination['page'] - 1 ?>">Prev</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pagination['totalPages']; $i++): ?>
                    <?php if ($i === $pagination['page']): ?>
                        <span class="active"><span><?= $i ?></span></span>
                    <?php else: ?>
                        <a href="/employer/jobs?page=<?= $i ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                    <a href="/employer/jobs?page=<?= $pagination['page'] + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <div class="empty-state">
            <h3>No job postings yet</h3>
            <p>Create your first job vacancy to start attracting talent.</p>
            <a href="/employer/jobs/create" class="btn btn-primary btn-lg">Create First Posting</a>
        </div>
    <?php endif; ?>
</div>