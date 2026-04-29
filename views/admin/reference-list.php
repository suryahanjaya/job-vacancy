<!-- Admin: Reference Table Management -->
<div class="container" style="padding: var(--space-8) 0;">
    <div class="admin-layout">
        <div class="admin-sidebar">
            <div class="card">
                <div class="card-header">
                    <h4>Reference Tables</h4>
                </div>
                <div class="card-body" style="padding: var(--space-3);">
                    <ul class="admin-sidebar-nav">
                        <?php
                        $navItems = [
                            'job_categories' => 'Job Categories',
                            'job_titles' => 'Job Titles',
                            'employment_types' => 'Employment Types',
                            'industries' => 'Industries',
                            'job_levels' => 'Job Levels',
                            'salary_ranges' => 'Salary Ranges',
                            'skills' => 'Skills',
                            'countries' => 'Countries',
                            'cities' => 'Cities',
                            'districts' => 'Districts',
                            'degree_levels' => 'Degree Levels',
                            'experience_levels' => 'Experience Levels',
                            'work_arrangements' => 'Work Arrangements',
                        ];
                        foreach ($navItems as $key => $label): ?>
                            <li><a href="/admin/reference/<?= $key ?>"
                                    class="<?= $tableName === $key ? 'active' : '' ?>"><?= $label ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-4"><a href="/dashboard" class="btn btn-secondary btn-sm btn-block">&larr; Back to
                    Dashboard</a></div>
        </div>

        <div class="admin-content">
            <div class="card mb-6">
                <div class="card-header">
                    <h3><?= h($tableLabel) ?></h3>
                    <span class="badge badge-primary"><?= count($items) ?> entries</span>
                </div>

                <div class="card-body" style="border-bottom: 1px solid var(--border-color);">
                    <form action="/admin/reference/<?= h($tableName) ?>/create" method="POST" class="d-flex gap-3"
                        style="flex-wrap: wrap; align-items: flex-end;">
                        <?= csrfField() ?>
                        <div class="form-group" style="margin: 0; flex: 1; min-width: 200px;">
                            <label
                                class="form-label"><?= in_array($tableName, ['salary_ranges', 'experience_levels']) ? 'Label' : 'Name' ?></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name..." required>
                        </div>

                        <?php if ($tableName === 'job_titles'): ?>
                            <div class="form-group" style="margin: 0; min-width: 180px;">
                                <label class="form-label">Category</label>
                                <select name="category_id" class="form-control">
                                    <option value="">None</option>
                                    <?php $refModel = new ReferenceModel();
                                    foreach ($refModel->getAll('job_categories') as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if ($tableName === 'cities'): ?>
                            <div class="form-group" style="margin: 0; min-width: 180px;">
                                <label class="form-label">Country</label>
                                <select name="country_id" class="form-control" required>
                                    <option value="">Select country</option>
                                    <?php $refModel = new ReferenceModel();
                                    foreach ($refModel->getAll('countries') as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if ($tableName === 'districts'): ?>
                            <div class="form-group" style="margin: 0; min-width: 180px;">
                                <label class="form-label">City</label>
                                <select name="city_id" class="form-control" required>
                                    <option value="">Select city</option>
                                    <?php $refModel = new ReferenceModel();
                                    foreach ($refModel->getAll('cities') as $c): ?>
                                        <option value="<?= $c['id'] ?>"><?= h($c['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>

                        <?php if ($tableName === 'countries'): ?>
                            <div class="form-group" style="margin: 0; min-width: 100px;">
                                <label class="form-label">Code</label>
                                <input type="text" name="code" class="form-control" placeholder="e.g. US" maxlength="5">
                            </div>
                        <?php endif; ?>

                        <?php if ($tableName === 'salary_ranges'): ?>
                            <div class="form-group" style="margin: 0; min-width: 120px;"><label class="form-label">Min
                                    Salary</label><input type="number" name="min_salary" class="form-control"
                                    placeholder="0" step="0.01"></div>
                            <div class="form-group" style="margin: 0; min-width: 120px;"><label class="form-label">Max
                                    Salary</label><input type="number" name="max_salary" class="form-control"
                                    placeholder="0" step="0.01"></div>
                        <?php endif; ?>

                        <?php if ($tableName === 'experience_levels'): ?>
                            <div class="form-group" style="margin: 0; min-width: 100px;"><label class="form-label">Min
                                    Years</label><input type="number" name="min_years" class="form-control" placeholder="0"
                                    min="0"></div>
                            <div class="form-group" style="margin: 0; min-width: 100px;"><label class="form-label">Max
                                    Years</label><input type="number" name="max_years" class="form-control" placeholder="">
                            </div>
                        <?php endif; ?>

                        <?php if (in_array($tableName, ['proficiency_levels', 'degree_levels'])): ?>
                            <div class="form-group" style="margin: 0; min-width: 100px;"><label
                                    class="form-label">Order</label><input type="number" name="level_order"
                                    class="form-control" placeholder="0" min="0"></div>
                        <?php endif; ?>

                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </div>

                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?= in_array($tableName, ['salary_ranges', 'experience_levels']) ? 'Label' : 'Name' ?>
                                    </th>
                                    <?php if ($tableName === 'job_titles'): ?>
                                        <th>Category ID</th><?php endif; ?>
                                    <?php if ($tableName === 'cities'): ?>
                                        <th>Country ID</th><?php endif; ?>
                                    <?php if ($tableName === 'districts'): ?>
                                        <th>City ID</th><?php endif; ?>
                                    <?php if ($tableName === 'countries'): ?>
                                        <th>Code</th><?php endif; ?>
                                    <?php if ($tableName === 'salary_ranges'): ?>
                                        <th>Min</th>
                                        <th>Max</th>
                                        <th>Currency</th><?php endif; ?>
                                    <?php if ($tableName === 'experience_levels'): ?>
                                        <th>Min Years</th>
                                        <th>Max Years</th><?php endif; ?>
                                    <?php if (in_array($tableName, ['proficiency_levels', 'degree_levels'])): ?>
                                        <th>Order</th><?php endif; ?>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td class="text-muted">#<?= $item['id'] ?></td>
                                        <td style="font-weight: 600;"><?= h($item['name'] ?? $item['label'] ?? '') ?></td>
                                        <?php if ($tableName === 'job_titles'): ?>
                                            <td><?= $item['category_id'] ?? '—' ?></td><?php endif; ?>
                                        <?php if ($tableName === 'cities'): ?>
                                            <td><?= $item['country_id'] ?? '—' ?></td><?php endif; ?>
                                        <?php if ($tableName === 'districts'): ?>
                                            <td><?= $item['city_id'] ?? '—' ?></td><?php endif; ?>
                                        <?php if ($tableName === 'countries'): ?>
                                            <td><?= h($item['code'] ?? '') ?></td><?php endif; ?>
                                        <?php if ($tableName === 'salary_ranges'): ?>
                                            <td><?= number_format($item['min_salary'], 0) ?></td>
                                            <td><?= number_format($item['max_salary'], 0) ?></td>
                                            <td><?= h($item['currency'] ?? 'USD') ?></td><?php endif; ?>
                                        <?php if ($tableName === 'experience_levels'): ?>
                                            <td><?= $item['min_years'] ?></td>
                                            <td><?= $item['max_years'] ?? '—' ?></td><?php endif; ?>
                                        <?php if (in_array($tableName, ['proficiency_levels', 'degree_levels'])): ?>
                                            <td><?= $item['level_order'] ?? 0 ?></td><?php endif; ?>
                                        <td>
                                            <form action="/admin/reference/<?= h($tableName) ?>/<?= $item['id'] ?>/delete"
                                                method="POST" style="display:inline;">
                                                <?= csrfField() ?>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="handleConfirm(this.form, 'Delete this entry?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>