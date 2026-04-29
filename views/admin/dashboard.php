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

    <!-- Charts -->
    <div class="card">
        <div class="card-header">
            <h3>Jobs Charts</h3>
        </div>
        <div class="card-body" style="position: relative; height: 500px;">
            <!-- Selection -->
            <div style="position:absolute; top:10px; right:10px; z-index:10;">
                <select id="groupBySelect">
                    <option value="" selected>-- Select --</option>
                    <optgroup label="Job Structure">
                        <option value="category">Category</option>
                        <option value="industry">Industry</option>
                        <option value="job_level">Job Level</option>
                    </optgroup>

                    <optgroup label="Employment">
                        <option value="employment_type">Employment Type</option>
                        <option value="work_arrangement">Work Arrangement</option>
                        <option value="experience_level">Experience Level</option>
                        <option value="salary_range">Salary Range</option>
                    </optgroup>

                    <optgroup label="Location">
                        <option value="country">Country</option>
                        <option value="city">City</option>
                    </optgroup>
                </select>
            </div>

            <!-- Empty state -->
            <div class="empty-state">
                <img src="/images/no-data.jpg" alt="No data" class="empty-image chart-view">
                <p style="font-size: var(--font-size-2xl);">No data</p>
            </div>

            <!-- Chart View -->
            <canvas id="jobsChart" data-chart='<?= json_encode($chartData) ?>'></canvas>
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