<!-- Home Page -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Find Your <span class="highlight">Dream Job</span> Today</h1>
            <p>Discover thousands of job opportunities from top employers. Search, filter, and find the perfect career
                match tailored to your skills and ambitions.</p>

            <form action="/jobs" method="GET" class="hero-search">
                <input type="text" name="keyword" class="form-control"
                    placeholder="Search job titles, skills, or keywords..." value="">
                <button type="submit" class="btn btn-primary btn-lg">Search</button>
            </form>

            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="stat-number"><?= $totalJobs ?? 0 ?></span>
                    <span class="stat-label">Active Jobs</span>
                </div>
                <div class="hero-stat">
                    <span class="stat-number"><?= $stats['employers'] ?? 0 ?></span>
                    <span class="stat-label">Employers</span>
                </div>
                <div class="hero-stat">
                    <span class="stat-number"><?= count($categories ?? []) ?></span>
                    <span class="stat-label">Categories</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Jobs -->
<section style="padding: var(--space-16) 0;">
    <div class="container">
        <div class="section-title">
            <h2>Latest Job Openings</h2>
            <p>Explore the most recently posted job vacancies</p>
        </div>

        <?php if (!empty($latestJobs)): ?>
            <div class="job-grid">
                <?php foreach ($latestJobs as $job): ?>
                    <a href="/jobs/<?= $job['id'] ?>" class="job-card">
                        <div class="job-card-header">
                            <div>
                                <div class="job-card-title"><?= h($job['job_title_name']) ?></div>
                                <div class="job-card-company"><?= h($job['company_name'] ?? $job['employer_name']) ?></div>
                            </div>
                            <span
                                class="badge badge-<?= $job['work_arrangement_name'] === 'Remote' ? 'success' : ($job['work_arrangement_name'] === 'Hybrid' ? 'warning' : 'info') ?>">
                                <?= h($job['work_arrangement_name']) ?>
                            </span>
                        </div>

                        <div class="job-card-meta">
                            <span class="job-card-meta-item"><?= h($job['city_name']) ?>, <?= h($job['country_name']) ?></span>
                            <span class="job-card-meta-item"><?= h($job['employment_type_name']) ?></span>
                            <span class="job-card-meta-item"><?= h($job['job_level_name']) ?></span>
                        </div>

                        <?php if (!empty($job['skills'])): ?>
                            <div class="job-card-skills">
                                <?php foreach (array_slice($job['skills'], 0, 3) as $skill): ?>
                                    <span class="skill-tag"><?= h($skill['skill_name']) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($job['skills']) > 3): ?>
                                    <span class="skill-tag">+<?= count($job['skills']) - 3 ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="job-card-footer">
                            <span class="job-card-salary"><?= h($job['salary_range_label']) ?></span>
                            <span class="job-card-time"><?= timeAgo($job['created_at']) ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-8">
                <a href="/jobs" class="btn btn-primary btn-lg">View All Jobs</a>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <h3>No jobs posted yet</h3>
                <p>Be the first employer to post a job vacancy!</p>
                <?php if (!isLoggedIn()): ?>
                    <a href="/register" class="btn btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Categories -->
<?php if (!empty($categories)): ?>
    <section style="padding: 0 0 var(--space-16);">
        <div class="container">
            <div class="section-title">
                <h2>Browse by Category</h2>
                <p>Explore jobs across various industries and domains</p>
            </div>

            <div class="categories-grid">
                <?php foreach ($categories as $cat): ?>
                    <a href="/jobs?job_category_id=<?= $cat['id'] ?>" class="category-card">
                        <div class="category-name"><?= h($cat['name']) ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>