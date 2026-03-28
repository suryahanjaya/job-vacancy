<!-- Job Search Page -->
<div class="container search-section">
    <div class="search-header">
        <h1>Find Your Next Opportunity</h1>
        <p>Search and filter through available job vacancies using multiple criteria</p>
    </div>

    <div class="search-filters">
        <form action="/jobs" method="GET" id="searchForm">
            <div class="form-group">
                <label class="form-label">Keyword Search</label>
                <input type="text" name="keyword" class="form-control"
                    placeholder="Search by job title, skills, or description..."
                    value="<?= h($filters['keyword'] ?? '') ?>">
            </div>

            <div class="filter-grid">
                <div class="form-group">
                    <label class="form-label">Job Category</label>
                    <select name="job_category_id" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($filters['job_category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= h($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <select name="country_id" class="form-control" onchange="loadCitiesForSearch(this.value)">
                        <option value="">All Countries</option>
                        <?php foreach ($countries as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= ($filters['country_id'] ?? '') == $c['id'] ? 'selected' : '' ?>><?= h($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">City</label>
                    <select name="city_id" class="form-control" id="searchCitySelect">
                        <option value="">All Cities</option>
                        <?php foreach ($cities as $ci): ?>
                            <option value="<?= $ci['id'] ?>" data-country="<?= $ci['country_id'] ?>" <?= ($filters['city_id'] ?? '') == $ci['id'] ? 'selected' : '' ?>><?= h($ci['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Employment Type</label>
                    <select name="employment_type_id" class="form-control">
                        <option value="">All Types</option>
                        <?php foreach ($employmentTypes as $et): ?>
                            <option value="<?= $et['id'] ?>" <?= ($filters['employment_type_id'] ?? '') == $et['id'] ? 'selected' : '' ?>><?= h($et['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Job Level</label>
                    <select name="job_level_id" class="form-control">
                        <option value="">All Levels</option>
                        <?php foreach ($jobLevels as $jl): ?>
                            <option value="<?= $jl['id'] ?>" <?= ($filters['job_level_id'] ?? '') == $jl['id'] ? 'selected' : '' ?>><?= h($jl['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Salary Range</label>
                    <select name="salary_range_id" class="form-control">
                        <option value="">All Salaries</option>
                        <?php foreach ($salaryRanges as $sr): ?>
                            <option value="<?= $sr['id'] ?>" <?= ($filters['salary_range_id'] ?? '') == $sr['id'] ? 'selected' : '' ?>><?= h($sr['label']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Work Arrangement</label>
                    <select name="work_arrangement_id" class="form-control">
                        <option value="">All Arrangements</option>
                        <?php foreach ($workArrangements as $wa): ?>
                            <option value="<?= $wa['id'] ?>" <?= ($filters['work_arrangement_id'] ?? '') == $wa['id'] ? 'selected' : '' ?>><?= h($wa['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Required Skill</label>
                    <select name="skill_id" class="form-control">
                        <option value="">All Skills</option>
                        <?php foreach ($skills as $sk): ?>
                            <option value="<?= $sk['id'] ?>" <?= ($filters['skill_id'] ?? '') == $sk['id'] ? 'selected' : '' ?>><?= h($sk['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Industry</label>
                    <select name="industry_id" class="form-control">
                        <option value="">All Industries</option>
                        <?php foreach ($industries as $ind): ?>
                            <option value="<?= $ind['id'] ?>" <?= ($filters['industry_id'] ?? '') == $ind['id'] ? 'selected' : '' ?>><?= h($ind['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="filter-actions">
                <a href="/jobs" class="btn btn-secondary">Clear Filters</a>
                <button type="submit" class="btn btn-primary">Search Jobs</button>
            </div>
        </form>
    </div>

    <div class="results-header">
        <div class="results-count">Found <span><?= $pagination['total'] ?? 0 ?></span>
            job(s)<?php if ($pagination['totalPages'] > 1): ?> &middot; Page <?= $pagination['page'] ?> of
                <?= $pagination['totalPages'] ?><?php endif; ?></div>
        <div class="sort-select">
            <label for="sortSelect">Sort by:</label>
            <select id="sortSelect" class="form-control" onchange="applySorting(this.value)">
                <option value="newest" <?= ($sort ?? '') === 'newest' ? 'selected' : '' ?>>Most Recent</option>
                <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Oldest First</option>
                <option value="salary_desc" <?= ($sort ?? '') === 'salary_desc' ? 'selected' : '' ?>>Salary: High to Low
                </option>
                <option value="salary_asc" <?= ($sort ?? '') === 'salary_asc' ? 'selected' : '' ?>>Salary: Low to High
                </option>
                <option value="title_asc" <?= ($sort ?? '') === 'title_asc' ? 'selected' : '' ?>>Title: A-Z</option>
                <option value="title_desc" <?= ($sort ?? '') === 'title_desc' ? 'selected' : '' ?>>Title: Z-A</option>
            </select>
        </div>
    </div>

    <?php if (!empty($jobs)): ?>
        <div class="job-grid">
            <?php foreach ($jobs as $job): ?>
                <a href="/jobs/<?= $job['id'] ?>" class="job-card">
                    <div class="job-card-header">
                        <div>
                            <div class="job-card-title"><?= h($job['job_title_name']) ?></div>
                            <div class="job-card-company"><?= h($job['company_name'] ?? $job['employer_name']) ?></div>
                        </div>
                        <span
                            class="badge badge-<?= $job['work_arrangement_name'] === 'Remote' ? 'success' : ($job['work_arrangement_name'] === 'Hybrid' ? 'warning' : 'info') ?>"><?= h($job['work_arrangement_name']) ?></span>
                    </div>
                    <div class="job-card-meta">
                        <span class="job-card-meta-item"><?= h($job['city_name']) ?>, <?= h($job['country_name']) ?></span>
                        <span class="job-card-meta-item"><?= h($job['employment_type_name']) ?></span>
                        <span class="job-card-meta-item"><?= h($job['job_level_name']) ?></span>
                        <span class="job-card-meta-item"><?= h($job['industry_name']) ?></span>
                    </div>
                    <?php if (!empty($job['skills'])): ?>
                        <div class="job-card-skills">
                            <?php foreach (array_slice($job['skills'], 0, 4) as $skill): ?>
                                <span class="skill-tag"><?= h($skill['skill_name']) ?></span>
                            <?php endforeach; ?>
                            <?php if (count($job['skills']) > 4): ?>
                                <span class="skill-tag">+<?= count($job['skills']) - 4 ?></span>
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

        <?php if ($pagination['totalPages'] > 1): ?>
            <div class="pagination">
                <?php $queryParams = $_GET;
                unset($queryParams['page']);
                $queryString = http_build_query($queryParams); ?>
                <?php if ($pagination['page'] > 1): ?>
                    <a href="/jobs?<?= $queryString ?>&page=<?= $pagination['page'] - 1 ?>">Prev</a>
                <?php endif; ?>
                <?php $start = max(1, $pagination['page'] - 2);
                $end = min($pagination['totalPages'], $pagination['page'] + 2); ?>
                <?php if ($start > 1): ?><a
                        href="/jobs?<?= $queryString ?>&page=1">1</a><?php if ($start > 2): ?><span>...</span><?php endif; ?><?php endif; ?>
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i === $pagination['page']): ?><span class="active"><span><?= $i ?></span></span>
                    <?php else: ?><a href="/jobs?<?= $queryString ?>&page=<?= $i ?>"><?= $i ?></a><?php endif; ?>
                <?php endfor; ?>
                <?php if ($end < $pagination['totalPages']): ?>            <?php if ($end < $pagination['totalPages'] - 1): ?><span>...</span><?php endif; ?><a
                        href="/jobs?<?= $queryString ?>&page=<?= $pagination['totalPages'] ?>"><?= $pagination['totalPages'] ?></a><?php endif; ?>
                <?php if ($pagination['page'] < $pagination['totalPages']): ?>
                    <a href="/jobs?<?= $queryString ?>&page=<?= $pagination['page'] + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state">
            <h3>No jobs found</h3>
            <p>Try adjusting your search criteria or clearing filters.</p>
            <a href="/jobs" class="btn btn-secondary">Clear All Filters</a>
        </div>
    <?php endif; ?>
</div>

<script>
    function applySorting(value) { const url = new URL(window.location); url.searchParams.set('sort', value); url.searchParams.delete('page'); window.location = url; }
    function loadCitiesForSearch(countryId) { const s = document.getElementById('searchCitySelect'); s.querySelectorAll('option').forEach(o => { if (o.value === '') o.style.display = ''; else if (!countryId || o.dataset.country === countryId) o.style.display = ''; else o.style.display = 'none'; }); s.value = ''; }
</script>