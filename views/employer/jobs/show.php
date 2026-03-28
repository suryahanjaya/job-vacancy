<!-- Employer: View Job Detail -->
<div class="container job-detail">
    <a href="/employer/jobs" class="btn btn-secondary btn-sm mb-6">&larr; Back to My Postings</a>

    <div class="job-detail-header">
        <div class="d-flex justify-between align-center" style="flex-wrap: wrap; gap: var(--space-4);">
            <div>
                <h1 class="job-detail-title"><?= h($job['job_title_name']) ?></h1>
                <div class="job-detail-company"><?= h($job['company_name'] ?? $job['employer_name']) ?></div>
            </div>
            <div class="btn-group">
                <a href="/employer/jobs/<?= $job['id'] ?>/edit" class="btn btn-primary btn-sm">Edit</a>
                <a href="/employer/jobs/<?= $job['id'] ?>/toggle"
                    class="btn btn-sm <?= $job['is_active'] ? 'btn-warning' : 'btn-success' ?>">
                    <?= $job['is_active'] ? 'Deactivate' : 'Activate' ?>
                </a>
            </div>
        </div>

        <div class="job-detail-tags mt-4">
            <span
                class="badge badge-<?= $job['is_active'] ? 'success' : 'warning' ?>"><?= $job['is_active'] ? 'Active' : 'Inactive' ?></span>
            <span class="badge badge-info"><?= h($job['employment_type_name']) ?></span>
            <span class="badge badge-primary"><?= h($job['job_level_name']) ?></span>
            <span class="badge badge-secondary"><?= h($job['work_arrangement_name']) ?></span>
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
                <div><span class="meta-label">Experience</span><span
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
                            style="margin-left: 4px; font-size: 10px;"><?= h($skill['proficiency_name']) ?></span></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="job-detail-section">
        <h3>Job Responsibilities</h3>
        <p class="description-text"><?= nl2br(h($job['responsibilities'])) ?></p>
    </div>
    <div class="job-detail-section">
        <h3>Required Qualifications</h3>
        <p class="description-text"><?= nl2br(h($job['qualifications'])) ?></p>
    </div>
    <?php if (!empty($job['preferred_skills_text'])): ?>
        <div class="job-detail-section">
            <h3>Preferred Skills</h3>
            <p class="description-text"><?= nl2br(h($job['preferred_skills_text'])) ?></p>
        </div>
    <?php endif; ?>
    <?php if (!empty($job['benefits'])): ?>
        <div class="job-detail-section">
            <h3>Benefits</h3>
            <p class="description-text"><?= nl2br(h($job['benefits'])) ?></p>
        </div>
    <?php endif; ?>
    <?php if (!empty($job['additional_notes'])): ?>
        <div class="job-detail-section">
            <h3>Additional Notes</h3>
            <p class="description-text"><?= nl2br(h($job['additional_notes'])) ?></p>
        </div>
    <?php endif; ?>

    <div class="text-muted text-center mt-6" style="font-size: var(--font-size-sm);">
        Posted <?= formatDate($job['created_at']) ?> &middot; Last updated <?= formatDate($job['updated_at']) ?>
    </div>
</div>