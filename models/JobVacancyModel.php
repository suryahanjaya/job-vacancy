<?php
/**
 * Job Vacancy Model
 */

class JobVacancyModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new job vacancy
     */
    public function create($data)
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "INSERT INTO job_vacancies (
                    employer_id, job_title_id, job_category_id, employment_type_id,
                    industry_id, job_level_id, num_openings, country_id, city_id,
                    district_id, work_arrangement_id, salary_range_id, salary_type_id,
                    benefits, responsibilities, qualifications, preferred_skills_text,
                    additional_notes, degree_level_id, experience_level_id, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );

            $stmt->execute([
                $data['employer_id'],
                $data['job_title_id'],
                $data['job_category_id'],
                $data['employment_type_id'],
                $data['industry_id'],
                $data['job_level_id'],
                $data['num_openings'],
                $data['country_id'],
                $data['city_id'],
                $data['district_id'] ?: null,
                $data['work_arrangement_id'],
                $data['salary_range_id'],
                $data['salary_type_id'],
                $data['benefits'] ?? null,
                $data['responsibilities'],
                $data['qualifications'],
                $data['preferred_skills_text'] ?? null,
                $data['additional_notes'] ?? null,
                $data['degree_level_id'],
                $data['experience_level_id'],
                $data['is_active'] ?? 1
            ]);

            $jobId = $this->db->lastInsertId();

            // Insert skills (many-to-many)
            if (!empty($data['skills'])) {
                $this->syncSkills($jobId, $data['skills']);
            }

            $this->db->commit();
            return $jobId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Update a job vacancy
     */
    public function update($id, $data)
    {
        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                "UPDATE job_vacancies SET
                    job_title_id = ?, job_category_id = ?, employment_type_id = ?,
                    industry_id = ?, job_level_id = ?, num_openings = ?,
                    country_id = ?, city_id = ?, district_id = ?,
                    work_arrangement_id = ?, salary_range_id = ?, salary_type_id = ?,
                    benefits = ?, responsibilities = ?, qualifications = ?,
                    preferred_skills_text = ?, additional_notes = ?,
                    degree_level_id = ?, experience_level_id = ?,
                    updated_at = NOW()
                WHERE id = ? AND employer_id = ?"
            );

            $stmt->execute([
                $data['job_title_id'],
                $data['job_category_id'],
                $data['employment_type_id'],
                $data['industry_id'],
                $data['job_level_id'],
                $data['num_openings'],
                $data['country_id'],
                $data['city_id'],
                $data['district_id'] ?: null,
                $data['work_arrangement_id'],
                $data['salary_range_id'],
                $data['salary_type_id'],
                $data['benefits'] ?? null,
                $data['responsibilities'],
                $data['qualifications'],
                $data['preferred_skills_text'] ?? null,
                $data['additional_notes'] ?? null,
                $data['degree_level_id'],
                $data['experience_level_id'],
                $id,
                $data['employer_id']
            ]);

            // Sync skills
            if (isset($data['skills'])) {
                $this->syncSkills($id, $data['skills']);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Sync skills for a job vacancy
     */
    private function syncSkills($jobId, $skills)
    {
        // Delete existing skills
        $stmt = $this->db->prepare("DELETE FROM job_vacancy_skills WHERE job_vacancy_id = ?");
        $stmt->execute([$jobId]);

        // Insert new skills (max 5)
        $skillCount = 0;
        $stmt = $this->db->prepare(
            "INSERT INTO job_vacancy_skills (job_vacancy_id, skill_id, proficiency_level_id) VALUES (?, ?, ?)"
        );

        foreach ($skills as $skill) {
            if ($skillCount >= MAX_SKILLS_PER_JOB)
                break;
            if (!empty($skill['skill_id']) && !empty($skill['proficiency_level_id'])) {
                $stmt->execute([$jobId, $skill['skill_id'], $skill['proficiency_level_id']]);
                $skillCount++;
            }
        }
    }

    /**
     * Delete a job vacancy
     */
    public function delete($id, $employerId = null)
    {
        $sql = "DELETE FROM job_vacancies WHERE id = ?";
        $params = [$id];

        if ($employerId) {
            $sql .= " AND employer_id = ?";
            $params[] = $employerId;
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id, $employerId = null)
    {
        $sql = "UPDATE job_vacancies SET is_active = NOT is_active, updated_at = NOW() WHERE id = ?";
        $params = [$id];

        if ($employerId) {
            $sql .= " AND employer_id = ?";
            $params[] = $employerId;
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Find by ID with full details
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare(
            "SELECT jv.*,
                jt.name AS job_title_name,
                jc.name AS job_category_name,
                et.name AS employment_type_name,
                ind.name AS industry_name,
                jl.name AS job_level_name,
                sr.label AS salary_range_label,
                sr.min_salary, sr.max_salary, sr.currency,
                st.name AS salary_type_name,
                c.name AS country_name, c.code AS country_code,
                ci.name AS city_name,
                d.name AS district_name,
                wa.name AS work_arrangement_name,
                dl.name AS degree_level_name,
                el.label AS experience_level_label,
                u.full_name AS employer_name,
                u.company_name,
                u.email AS employer_email
            FROM job_vacancies jv
            JOIN job_titles jt ON jv.job_title_id = jt.id
            JOIN job_categories jc ON jv.job_category_id = jc.id
            JOIN employment_types et ON jv.employment_type_id = et.id
            JOIN industries ind ON jv.industry_id = ind.id
            JOIN job_levels jl ON jv.job_level_id = jl.id
            JOIN salary_ranges sr ON jv.salary_range_id = sr.id
            JOIN salary_types st ON jv.salary_type_id = st.id
            JOIN countries c ON jv.country_id = c.id
            JOIN cities ci ON jv.city_id = ci.id
            LEFT JOIN districts d ON jv.district_id = d.id
            JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
            JOIN degree_levels dl ON jv.degree_level_id = dl.id
            JOIN experience_levels el ON jv.experience_level_id = el.id
            JOIN users u ON jv.employer_id = u.id
            WHERE jv.id = ?"
        );

        $stmt->execute([$id]);
        $job = $stmt->fetch();

        if ($job) {
            $job['skills'] = $this->getJobSkills($id);
        }

        return $job;
    }

    /**
     * Get skills for a job
     */
    public function getJobSkills($jobId)
    {
        $stmt = $this->db->prepare(
            "SELECT jvs.*, s.name AS skill_name, pl.name AS proficiency_name, pl.level_order
             FROM job_vacancy_skills jvs
             JOIN skills s ON jvs.skill_id = s.id
             JOIN proficiency_levels pl ON jvs.proficiency_level_id = pl.id
             WHERE jvs.job_vacancy_id = ?
             ORDER BY pl.level_order DESC"
        );
        $stmt->execute([$jobId]);
        return $stmt->fetchAll();
    }

    /**
     * Get jobs by employer
     */
    public function getByEmployer($employerId, $page = 1, $perPage = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;

        // Count total
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM job_vacancies WHERE employer_id = ?");
        $countStmt->execute([$employerId]);
        $total = $countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT jv.*,
                jt.name AS job_title_name,
                jc.name AS job_category_name,
                et.name AS employment_type_name,
                jl.name AS job_level_name,
                sr.label AS salary_range_label,
                ci.name AS city_name,
                c.name AS country_name,
                wa.name AS work_arrangement_name
            FROM job_vacancies jv
            JOIN job_titles jt ON jv.job_title_id = jt.id
            JOIN job_categories jc ON jv.job_category_id = jc.id
            JOIN employment_types et ON jv.employment_type_id = et.id
            JOIN job_levels jl ON jv.job_level_id = jl.id
            JOIN salary_ranges sr ON jv.salary_range_id = sr.id
            JOIN cities ci ON jv.city_id = ci.id
            JOIN countries c ON jv.country_id = c.id
            JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
            WHERE jv.employer_id = ?
            ORDER BY jv.created_at DESC
            LIMIT ? OFFSET ?"
        );

        $stmt->execute([$employerId, $perPage, $offset]);
        $jobs = $stmt->fetchAll();

        return [
            'data' => $jobs,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Search jobs with multiple criteria
     */
    public function search($filters = [], $page = 1, $perPage = ITEMS_PER_PAGE, $sort = 'newest')
    {
        $offset = ($page - 1) * $perPage;
        $conditions = ["jv.is_active = 1"];
        $params = [];

        // Keyword search (title + description)
        if (!empty($filters['keyword'])) {
            $keyword = '%' . $filters['keyword'] . '%';
            $conditions[] = "(jt.name LIKE ? OR jv.responsibilities LIKE ? OR jv.qualifications LIKE ?)";
            $params[] = $keyword;
            $params[] = $keyword;
            $params[] = $keyword;
        }

        // Job category
        if (!empty($filters['job_category_id'])) {
            $conditions[] = "jv.job_category_id = ?";
            $params[] = $filters['job_category_id'];
        }

        // Country
        if (!empty($filters['country_id'])) {
            $conditions[] = "jv.country_id = ?";
            $params[] = $filters['country_id'];
        }

        // City
        if (!empty($filters['city_id'])) {
            $conditions[] = "jv.city_id = ?";
            $params[] = $filters['city_id'];
        }

        // Employment type
        if (!empty($filters['employment_type_id'])) {
            $conditions[] = "jv.employment_type_id = ?";
            $params[] = $filters['employment_type_id'];
        }

        // Job level
        if (!empty($filters['job_level_id'])) {
            $conditions[] = "jv.job_level_id = ?";
            $params[] = $filters['job_level_id'];
        }

        // Salary range
        if (!empty($filters['salary_range_id'])) {
            $conditions[] = "jv.salary_range_id = ?";
            $params[] = $filters['salary_range_id'];
        }

        // Work arrangement
        if (!empty($filters['work_arrangement_id'])) {
            $conditions[] = "jv.work_arrangement_id = ?";
            $params[] = $filters['work_arrangement_id'];
        }

        // Required skill
        if (!empty($filters['skill_id'])) {
            $conditions[] = "EXISTS (SELECT 1 FROM job_vacancy_skills jvs WHERE jvs.job_vacancy_id = jv.id AND jvs.skill_id = ?)";
            $params[] = $filters['skill_id'];
        }

        // Industry
        if (!empty($filters['industry_id'])) {
            $conditions[] = "jv.industry_id = ?";
            $params[] = $filters['industry_id'];
        }

        $whereClause = implode(' AND ', $conditions);

        // Sorting
        switch ($sort) {
            case 'salary_asc':
                $orderBy = "sr.min_salary ASC";
                break;
            case 'salary_desc':
                $orderBy = "sr.min_salary DESC";
                break;
            case 'title_asc':
                $orderBy = "jt.name ASC";
                break;
            case 'title_desc':
                $orderBy = "jt.name DESC";
                break;
            case 'oldest':
                $orderBy = "jv.created_at ASC";
                break;
            default:
                $orderBy = "jv.created_at DESC";
        }

        // Count total results
        $countSql = "SELECT COUNT(DISTINCT jv.id)
            FROM job_vacancies jv
            JOIN job_titles jt ON jv.job_title_id = jt.id
            JOIN job_categories jc ON jv.job_category_id = jc.id
            JOIN salary_ranges sr ON jv.salary_range_id = sr.id
            WHERE $whereClause";

        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn();

        // Fetch results
        $sql = "SELECT DISTINCT jv.*,
            jt.name AS job_title_name,
            jc.name AS job_category_name,
            et.name AS employment_type_name,
            ind.name AS industry_name,
            jl.name AS job_level_name,
            sr.label AS salary_range_label,
            sr.min_salary, sr.max_salary,
            st.name AS salary_type_name,
            c.name AS country_name,
            ci.name AS city_name,
            wa.name AS work_arrangement_name,
            u.company_name,
            u.full_name AS employer_name
        FROM job_vacancies jv
        JOIN job_titles jt ON jv.job_title_id = jt.id
        JOIN job_categories jc ON jv.job_category_id = jc.id
        JOIN employment_types et ON jv.employment_type_id = et.id
        JOIN industries ind ON jv.industry_id = ind.id
        JOIN job_levels jl ON jv.job_level_id = jl.id
        JOIN salary_ranges sr ON jv.salary_range_id = sr.id
        JOIN salary_types st ON jv.salary_type_id = st.id
        JOIN countries c ON jv.country_id = c.id
        JOIN cities ci ON jv.city_id = ci.id
        JOIN work_arrangements wa ON jv.work_arrangement_id = wa.id
        JOIN users u ON jv.employer_id = u.id
        WHERE $whereClause
        ORDER BY $orderBy
        LIMIT ? OFFSET ?";

        $params[] = $perPage;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $jobs = $stmt->fetchAll();

        // Get skills for each job
        foreach ($jobs as &$job) {
            $job['skills'] = $this->getJobSkills($job['id']);
        }

        return [
            'data' => $jobs,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / max($perPage, 1))
        ];
    }

    /**
     * Get all jobs (admin)
     */
    public function getAll($page = 1, $perPage = ITEMS_PER_PAGE)
    {
        $offset = ($page - 1) * $perPage;

        $countStmt = $this->db->query("SELECT COUNT(*) FROM job_vacancies");
        $total = $countStmt->fetchColumn();

        $stmt = $this->db->prepare(
            "SELECT jv.*,
                jt.name AS job_title_name,
                jc.name AS job_category_name,
                et.name AS employment_type_name,
                jl.name AS job_level_name,
                sr.label AS salary_range_label,
                ci.name AS city_name,
                c.name AS country_name,
                u.company_name,
                u.full_name AS employer_name
            FROM job_vacancies jv
            JOIN job_titles jt ON jv.job_title_id = jt.id
            JOIN job_categories jc ON jv.job_category_id = jc.id
            JOIN employment_types et ON jv.employment_type_id = et.id
            JOIN job_levels jl ON jv.job_level_id = jl.id
            JOIN salary_ranges sr ON jv.salary_range_id = sr.id
            JOIN cities ci ON jv.city_id = ci.id
            JOIN countries c ON jv.country_id = c.id
            JOIN users u ON jv.employer_id = u.id
            ORDER BY jv.created_at DESC
            LIMIT ? OFFSET ?"
        );

        $stmt->execute([$perPage, $offset]);
        $jobs = $stmt->fetchAll();

        return [
            'data' => $jobs,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => ceil($total / $perPage)
        ];
    }

    /**
     * Get statistics
     */
    public function getStats($employerId = null)
    {
        $stats = [];

        if ($employerId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM job_vacancies WHERE employer_id = ?");
            $stmt->execute([$employerId]);
            $stats['total'] = $stmt->fetchColumn();

            $stmt = $this->db->prepare("SELECT COUNT(*) FROM job_vacancies WHERE employer_id = ? AND is_active = 1");
            $stmt->execute([$employerId]);
            $stats['active'] = $stmt->fetchColumn();

            $stats['inactive'] = $stats['total'] - $stats['active'];
        } else {
            $stats['total'] = $this->db->query("SELECT COUNT(*) FROM job_vacancies")->fetchColumn();
            $stats['active'] = $this->db->query("SELECT COUNT(*) FROM job_vacancies WHERE is_active = 1")->fetchColumn();
            $stats['inactive'] = $stats['total'] - $stats['active'];
            $stats['employers'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'employer'")->fetchColumn();
            $stats['jobseekers'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'jobseeker'")->fetchColumn();
        }

        return $stats;
    }
}
