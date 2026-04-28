<!-- Employer: Edit Job Posting -->
<div class="container" style="padding: var(--space-8) 0; max-width: 900px;">
    <div class="mb-6">
        <a href="/employer/jobs" class="btn btn-secondary btn-sm mb-4">&larr; Back to My Postings</a>
        <h1>Edit Job Posting</h1>
        <p class="text-muted">Update the details of your job vacancy</p>
    </div>

    <form action="/employer/jobs/<?= $job['id'] ?>/update" method="POST" id="jobForm">
        <?= csrfField() ?>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">A. Basic Job Information</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Category <span class="required">*</span></label>
                        <select name="job_category_id" class="form-control" id="jobCategory" required
                            onchange="loadJobTitles(this.value)">
                            <option value="">Select category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $job['job_category_id'] == $cat['id'] ? 'selected' : '' ?>><?= h($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Job Title <span class="required">*</span></label>
                        <select name="job_title_id" class="form-control" id="jobTitle" required>
                            <option value="">Select title</option>
                            <?php foreach ($jobTitles as $t): ?>
                                <option value="<?= $t['id'] ?>" data-category="<?= $t['category_id'] ?>"
                                    <?= $job['job_title_id'] == $t['id'] ? 'selected' : '' ?>><?= h($t['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Employment Type <span class="required">*</span></label>
                        <select name="employment_type_id" class="form-control" required>
                            <option value="">Select type</option>
                            <?php foreach ($employmentTypes as $et): ?>
                                <option value="<?= $et['id'] ?>" <?= $job['employment_type_id'] == $et['id'] ? 'selected' : '' ?>><?= h($et['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Industry <span class="required">*</span></label>
                        <select name="industry_id" class="form-control" required>
                            <option value="">Select industry</option>
                            <?php foreach ($industries as $ind): ?>
                                <option value="<?= $ind['id'] ?>" <?= $job['industry_id'] == $ind['id'] ? 'selected' : '' ?>>
                                    <?= h($ind['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Job Level <span class="required">*</span></label>
                        <select name="job_level_id" class="form-control" required>
                            <option value="">Select level</option>
                            <?php foreach ($jobLevels as $jl): ?>
                                <option value="<?= $jl['id'] ?>" <?= $job['job_level_id'] == $jl['id'] ? 'selected' : '' ?>>
                                    <?= h($jl['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Number of Openings <span class="required">*</span></label>
                        <input type="number" name="num_openings" class="form-control" min="1"
                            value="<?= h($job['num_openings']) ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">B. Job Location</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Country <span class="required">*</span></label>
                        <select name="country_id" class="form-control" id="countrySelect" required
                            onchange="loadCities(this.value)">
                            <option value="">Select country</option>
                            <?php foreach ($countries as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $job['country_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= h($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">City / Province <span class="required">*</span></label>
                        <select name="city_id" class="form-control" id="citySelect" required
                            onchange="loadDistricts(this.value)">
                            <option value="">Select city</option>
                            <?php foreach ($cities as $ci): ?>
                                <option value="<?= $ci['id'] ?>" data-country="<?= $ci['country_id'] ?>"
                                    <?= $job['city_id'] == $ci['id'] ? 'selected' : '' ?>><?= h($ci['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">District (optional)</label>
                        <select name="district_id" class="form-control" id="districtSelect">
                            <option value="">Select district</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?= $d['id'] ?>" data-city="<?= $d['city_id'] ?>"
                                    <?= $job['district_id'] == $d['id'] ? 'selected' : '' ?>><?= h($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Work Arrangement <span class="required">*</span></label>
                        <select name="work_arrangement_id" class="form-control" required>
                            <option value="">Select arrangement</option>
                            <?php foreach ($workArrangements as $wa): ?>
                                <option value="<?= $wa['id'] ?>" <?= $job['work_arrangement_id'] == $wa['id'] ? 'selected' : '' ?>><?= h($wa['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">C. Salary &amp; Benefits</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Salary Range <span class="required">*</span></label>
                        <select name="salary_range_id" class="form-control" required>
                            <option value="">Select salary range</option>
                            <?php foreach ($salaryRanges as $sr): ?>
                                <option value="<?= $sr['id'] ?>" <?= $job['salary_range_id'] == $sr['id'] ? 'selected' : '' ?>>
                                    <?= h($sr['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Salary Type <span class="required">*</span></label>
                        <select name="salary_type_id" class="form-control" required>
                            <option value="">Select type</option>
                            <?php foreach ($salaryTypes as $st): ?>
                                <option value="<?= $st['id'] ?>" <?= $job['salary_type_id'] == $st['id'] ? 'selected' : '' ?>>
                                    <?= h($st['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Benefits</label>
                    <textarea name="benefits" class="form-control" rows="3"><?= h($job['benefits']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">D. Job Description</div>
                <div class="form-group">
                    <label class="form-label">Job Responsibilities <span class="required">*</span></label>
                    <textarea name="responsibilities" class="form-control" rows="5"
                        required><?= h($job['responsibilities']) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Required Qualifications <span class="required">*</span></label>
                    <textarea name="qualifications" class="form-control" rows="5"
                        required><?= h($job['qualifications']) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Preferred Skills</label>
                    <textarea name="preferred_skills_text" class="form-control"
                        rows="3"><?= h($job['preferred_skills_text']) ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Additional Notes</label>
                    <textarea name="additional_notes" class="form-control"
                        rows="3"><?= h($job['additional_notes']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">E. Required Skills <span class="required">*</span></div>
                <div class="skills-container" id="skillsContainer">
                    <?php if (!empty($job['skills'])): ?>
                        <?php foreach ($job['skills'] as $i => $js): ?>
                            <div class="skill-row" data-index="<?= $i ?>">
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Skill</label>
                                    <select name="skills[<?= $i ?>][skill_id]" class="form-control skill-select" required>
                                        <option value="">Select skill</option>
                                        <?php foreach ($skills as $skill): ?>
                                            <option value="<?= $skill['id'] ?>" <?= $js['skill_id'] == $skill['id'] ? 'selected' : '' ?>><?= h($skill['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group" style="margin: 0;">
                                    <label class="form-label">Min. Proficiency</label>
                                    <select name="skills[<?= $i ?>][proficiency_level_id]" class="form-control" required>
                                        <option value="">Select level</option>
                                        <?php foreach ($proficiencyLevels as $pl): ?>
                                            <option value="<?= $pl['id'] ?>" <?= $js['proficiency_level_id'] == $pl['id'] ? 'selected' : '' ?>><?= h($pl['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <button type="button" class="btn-remove" onclick="removeSkillRow(this)"
                                    style="align-self: end;">✕</button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="skill-row" data-index="0">
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Skill</label>
                                <select name="skills[0][skill_id]" class="form-control skill-select" required>
                                    <option value="">Select skill</option>
                                    <?php foreach ($skills as $skill): ?>
                                        <option value="<?= $skill['id'] ?>"><?= h($skill['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group" style="margin: 0;">
                                <label class="form-label">Min. Proficiency</label>
                                <select name="skills[0][proficiency_level_id]" class="form-control" required>
                                    <option value="">Select level</option>
                                    <?php foreach ($proficiencyLevels as $pl): ?>
                                        <option value="<?= $pl['id'] ?>"><?= h($pl['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="btn-remove" onclick="removeSkillRow(this)"
                                style="align-self: end;">✕</button>
                        </div>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-outline btn-sm add-skill-btn" id="addSkillBtn"
                    onclick="addSkillRow()">+ Add Skill</button>
                <div class="skills-count" id="skillsCount"><?= max(1, count($job['skills'] ?? [])) ?> /
                    <?= MAX_SKILLS_PER_JOB ?> skills</div>
            </div>
        </div>

        <div class="card mb-6">
            <div class="card-body">
                <div class="form-section-title">F. Education &amp; Experience Requirements</div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Minimum Degree Level <span class="required">*</span></label>
                        <select name="degree_level_id" class="form-control" required>
                            <option value="">Select degree</option>
                            <?php foreach ($degreeLevels as $dl): ?>
                                <option value="<?= $dl['id'] ?>" <?= $job['degree_level_id'] == $dl['id'] ? 'selected' : '' ?>>
                                    <?= h($dl['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Minimum Experience <span class="required">*</span></label>
                        <select name="experience_level_id" class="form-control" required>
                            <option value="">Select experience</option>
                            <?php foreach ($experienceLevels as $el): ?>
                                <option value="<?= $el['id'] ?>" <?= $job['experience_level_id'] == $el['id'] ? 'selected' : '' ?>><?= h($el['label']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-between" style="flex-wrap: wrap; gap: var(--space-4);">
            <a href="/employer/jobs" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary btn-lg">Update Job Posting</button>
        </div>
    </form>
</div>

<script>
    const MAX_SKILLS = <?= MAX_SKILLS_PER_JOB ?>;
    const skillOptions = <?= json_encode($skills) ?>;
    const proficiencyOptions = <?= json_encode($proficiencyLevels) ?>;
</script>