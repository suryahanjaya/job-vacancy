<!-- Employer Dashboard -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="dashboard-header">
        <h1>Welcome, <?= h(getUserName()) ?></h1>
        <p>Manage your job postings and track your recruitment activity</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                <div class="stat-label">Total Postings</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['active'] ?? 0 ?></div>
                <div class="stat-label">Active Jobs</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['inactive'] ?? 0 ?></div>
                <div class="stat-label">Inactive Jobs</div>
            </div>
        </div>
    </div>

    <div class="card mb-6">
        <div class="card-header"><h3>Quick Actions</h3></div>
        <div class="card-body">
            <div class="btn-group">
                <a href="/employer/jobs/create" class="btn btn-primary">Create New Job Posting</a>
                <a href="/employer/jobs" class="btn btn-secondary">View All My Postings</a>
                <a href="/jobs" class="btn btn-outline">Browse Job Board</a>
            </div>
        </div>
    </div>

    <?php if (!empty($recentJobs)): ?>
    <div class="card">
        <div class="card-header">
            <h3>Recent Postings</h3>
            <a href="/employer/jobs" class="btn btn-sm btn-secondary">View All</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Posted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentJobs as $job): ?>
                        <tr>
                            <td><a href="/employer/jobs/<?= $job['id'] ?>" style="font-weight: 600; color: var(--text-primary);"><?= h($job['job_title_name']) ?></a></td>
                            <td><?= h($job['job_category_name']) ?></td>
                            <td><?= h($job['city_name']) ?>, <?= h($job['country_name']) ?></td>
                            <td><span class="badge badge-<?= $job['is_active'] ? 'success' : 'warning' ?>"><?= $job['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                            <td class="text-muted"><?= timeAgo($job['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>