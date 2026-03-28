<!-- Admin Dashboard -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>System overview and management tools</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total'] ?? 0 ?></div>
                <div class="stat-label">Total Job Postings</div>
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
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['employers'] ?? 0 ?></div>
                <div class="stat-label">Employers</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-content">
                <div class="stat-value"><?= $stats['jobseekers'] ?? 0 ?></div>
                <div class="stat-label">Job Seekers</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Management Tools</h3>
        </div>
        <div class="card-body">
            <div
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: var(--space-4);">
                <a href="/admin/jobs" class="category-card">
                    <div class="category-name">Job Postings</div>
                </a>
                <a href="/admin/users" class="category-card">
                    <div class="category-name">Users</div>
                </a>
                <a href="/admin/reference/job_categories" class="category-card">
                    <div class="category-name">Job Categories</div>
                </a>
                <a href="/admin/reference/job_titles" class="category-card">
                    <div class="category-name">Job Titles</div>
                </a>
                <a href="/admin/reference/skills" class="category-card">
                    <div class="category-name">Skills</div>
                </a>
                <a href="/admin/reference/industries" class="category-card">
                    <div class="category-name">Industries</div>
                </a>
                <a href="/admin/reference/employment_types" class="category-card">
                    <div class="category-name">Employment Types</div>
                </a>
                <a href="/admin/reference/countries" class="category-card">
                    <div class="category-name">Locations</div>
                </a>
                <a href="/admin/reference/salary_ranges" class="category-card">
                    <div class="category-name">Salary Ranges</div>
                </a>
                <a href="/admin/reference/degree_levels" class="category-card">
                    <div class="category-name">Degree Levels</div>
                </a>
                <a href="/admin/reference/work_arrangements" class="category-card">
                    <div class="category-name">Work Arrangements</div>
                </a>
                <a href="/admin/reference/experience_levels" class="category-card">
                    <div class="category-name">Experience Levels</div>
                </a>
            </div>
        </div>
    </div>
</div>