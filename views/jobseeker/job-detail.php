<!-- Job Detail Page (Public View) -->
<div class="container job-detail">
    <a href="/jobs" class="btn btn-secondary btn-sm mb-6">&larr; Back to Search Results</a>

    <div class="job-detail-header">
        <h1 class="job-detail-title"><?= h($job['job_title_name']) ?></h1>
        <div class="job-detail-company"><?= h($job['company_name'] ?? $job['employer_name']) ?></div>

        <div class="job-detail-tags mt-4">
            <span class="badge badge-info"><?= h($job['employment_type_name']) ?></span>
            <span class="badge badge-primary"><?= h($job['job_category_name']) ?></span>
            <span class="badge badge-primary"><?= h($job['job_level_name']) ?></span>
            <span
                class="badge badge-<?= $job['work_arrangement_name'] === 'Remote' ? 'success' : 'secondary' ?>"><?= h($job['work_arrangement_name']) ?></span>
        </div>

        <div class="job-detail-meta mt-5">
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Location</span><span class="meta-value"><?= h($job['city_name']) ?>,
                        <?= h($job['country_name']) ?><?= $job['district_name'] ? ' (' . h($job['district_name']) . ')' : '' ?></span>
                </div>
            </div>
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Salary (<?= h($job['salary_type_name']) ?>)</span><span
                        class="meta-value"><?= h($job['salary_range_label']) ?></span></div>
            </div>
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Industry</span><span
                        class="meta-value"><?= h($job['industry_name']) ?></span></div>
            </div>
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Openings</span><span class="meta-value"><?= h($job['num_openings']) ?>
                        position(s)</span></div>
            </div>
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Min. Education</span><span
                        class="meta-value"><?= h($job['degree_level_name']) ?></span></div>
            </div>
            <div class="job-detail-meta-item">
                <div><span class="meta-label">Experience Required</span><span
                        class="meta-value"><?= h($job['experience_level_label']) ?></span></div>
            </div>
        </div>
    </div>

    <?php if (!empty($job['skills'])): ?>
        <div class="job-detail-section">
            <h3>Required Skills</h3>
            <div style="display: flex; flex-wrap: wrap; gap: var(--space-3);">
                <?php foreach ($job['skills'] as $skill): ?>
                    <div class="skill-tag" style="padding: var(--space-2) var(--space-4);"><?= h($skill['skill_name']) ?> <span
                            class="badge badge-primary"
                            style="margin-left: 6px; font-size: 10px;"><?= h($skill['proficiency_name']) ?></span></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="job-detail-section">
        <h3>Job Responsibilities</h3>
        <div class="description-text" style="color: var(--text-secondary); line-height: 1.8;">
            <?= nl2br(h($job['responsibilities'])) ?></div>
    </div>
    <div class="job-detail-section">
        <h3>Required Qualifications</h3>
        <div class="description-text" style="color: var(--text-secondary); line-height: 1.8;">
            <?= nl2br(h($job['qualifications'])) ?></div>
    </div>
    <?php if (!empty($job['preferred_skills_text'])): ?>
        <div class="job-detail-section">
            <h3>Preferred Skills</h3>
            <div class="description-text" style="color: var(--text-secondary); line-height: 1.8;">
                <?= nl2br(h($job['preferred_skills_text'])) ?></div>
        </div>
    <?php endif; ?>
    <?php if (!empty($job['benefits'])): ?>
        <div class="job-detail-section">
            <h3>Benefits &amp; Perks</h3>
            <div class="description-text" style="color: var(--text-secondary); line-height: 1.8;">
                <?= nl2br(h($job['benefits'])) ?></div>
        </div>
    <?php endif; ?>
    <?php if (!empty($job['additional_notes'])): ?>
        <div class="job-detail-section">
            <h3>Additional Information</h3>
            <div class="description-text" style="color: var(--text-secondary); line-height: 1.8;">
                <?= nl2br(h($job['additional_notes'])) ?></div>
        </div>
    <?php endif; ?>

    <div class="job-detail-section">
        <h3>About the Employer</h3>
        <div style="display: flex; align-items: center; gap: var(--space-4);">
            <div class="user-avatar" style="width: 56px; height: 56px; font-size: var(--font-size-xl);">
                <?= strtoupper(substr($job['employer_name'], 0, 1)) ?></div>
            <div>
                <div style="font-weight: 700; font-size: var(--font-size-lg); color: var(--text-primary);">
                    <?= h($job['company_name'] ?? $job['employer_name']) ?></div>
                <div style="color: var(--text-secondary); font-size: var(--font-size-sm);">Contact:
                    <?= h($job['employer_name']) ?></div>
            </div>
        </div>
    </div>

    <div class="text-center mt-6"
        style="padding: var(--space-6); background: var(--gradient-card); border: 1px solid var(--border-color); border-radius: var(--border-radius);">
        <p class="text-muted" style="font-size: var(--font-size-sm); margin-bottom: var(--space-3);">Posted
            <?= formatDate($job['created_at']) ?> &middot; <?= timeAgo($job['created_at']) ?></p>
        <a href="/jobs" class="btn btn-primary">&larr; Back to Job Search</a>
    </div>
</div>