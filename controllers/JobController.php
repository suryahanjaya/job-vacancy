<?php
/**
 * Job Vacancy Controller (Employer)
 */

require_once APP_ROOT . '/models/JobVacancyModel.php';
require_once APP_ROOT . '/models/ReferenceModel.php';

class JobController
{
    private $jobModel;
    private $refModel;

    public function __construct()
    {
        $this->jobModel = new JobVacancyModel();
        $this->refModel = new ReferenceModel();
    }

    /**
     * List employer's jobs
     */
    public function index()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $result = $this->jobModel->getByEmployer(getUserId(), $page);

        view('employer/jobs/index', [
            'title' => 'My Job Postings',
            'jobs' => $result['data'],
            'pagination' => $result
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $referenceData = $this->loadReferenceData();

        view('employer/jobs/create', array_merge([
            'title' => 'Create Job Posting'
        ], $referenceData));
    }

    /**
     * Store new job
     */
    public function store()
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/employer/jobs/create');
            return;
        }

        $data = $this->extractFormData();
        $data['employer_id'] = getUserId();

        // Validate
        $errors = $this->validateJobData($data);
        if (!empty($errors)) {
            setErrors($errors);
            setOldInput($_POST);
            redirect('/employer/jobs/create');
            return;
        }

        try {
            $jobId = $this->jobModel->create($data);
            clearOldInput();
            setFlash('success', 'Job posting created successfully!');
            redirect('/employer/jobs');
        } catch (Exception $e) {
            setFlash('error', 'Failed to create job posting: ' . $e->getMessage());
            setOldInput($_POST);
            redirect('/employer/jobs/create');
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $job = $this->jobModel->findById($id);

        if (!$job || $job['employer_id'] != getUserId()) {
            setFlash('error', 'Job posting not found or access denied.');
            redirect('/employer/jobs');
            return;
        }

        $referenceData = $this->loadReferenceData();

        view('employer/jobs/edit', array_merge([
            'title' => 'Edit Job Posting',
            'job' => $job
        ], $referenceData));
    }

    /**
     * Update job
     */
    public function update($id)
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect("/employer/jobs/$id/edit");
            return;
        }

        $job = $this->jobModel->findById($id);

        if (!$job || $job['employer_id'] != getUserId()) {
            setFlash('error', 'Job posting not found or access denied.');
            redirect('/employer/jobs');
            return;
        }

        $data = $this->extractFormData();
        $data['employer_id'] = getUserId();

        $errors = $this->validateJobData($data);
        if (!empty($errors)) {
            setErrors($errors);
            setOldInput($_POST);
            redirect("/employer/jobs/$id/edit");
            return;
        }

        try {
            $this->jobModel->update($id, $data);
            clearOldInput();
            setFlash('success', 'Job posting updated successfully!');
            redirect('/employer/jobs');
        } catch (Exception $e) {
            setFlash('error', 'Failed to update job posting.');
            setOldInput($_POST);
            redirect("/employer/jobs/$id/edit");
        }
    }

    /**
     * Delete job
     */
    public function delete($id)
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/employer/jobs');
            return;
        }

        $result = $this->jobModel->delete($id, getUserId());

        if ($result) {
            setFlash('success', 'Job posting deleted successfully.');
        } else {
            setFlash('error', 'Failed to delete job posting.');
        }

        redirect('/employer/jobs');
    }

    /**
     * Toggle active status
     */
    public function toggleActive($id)
    {
        $result = $this->jobModel->toggleActive($id, getUserId());

        if ($result) {
            setFlash('success', 'Job posting status updated.');
        } else {
            setFlash('error', 'Failed to update status.');
        }

        redirect('/employer/jobs');
    }

    /**
     * View single job (employer view)
     */
    public function show($id)
    {
        $job = $this->jobModel->findById($id);

        if (!$job || $job['employer_id'] != getUserId()) {
            setFlash('error', 'Job posting not found.');
            redirect('/employer/jobs');
            return;
        }

        view('employer/jobs/show', [
            'title' => $job['job_title_name'],
            'job' => $job
        ]);
    }

    /**
     * Load all reference data for forms
     */
    private function loadReferenceData()
    {
        return [
            'categories' => $this->refModel->getAll('job_categories'),
            'jobTitles' => $this->refModel->getAll('job_titles'),
            'employmentTypes' => $this->refModel->getAll('employment_types'),
            'industries' => $this->refModel->getAll('industries'),
            'jobLevels' => $this->refModel->getAll('job_levels'),
            'salaryRanges' => $this->refModel->getAll('salary_ranges'),
            'salaryTypes' => $this->refModel->getAll('salary_types'),
            'skills' => $this->refModel->getAll('skills'),
            'proficiencyLevels' => $this->refModel->getAll('proficiency_levels'),
            'countries' => $this->refModel->getAll('countries'),
            'cities' => $this->refModel->getAll('cities'),
            'districts' => $this->refModel->getAll('districts'),
            'degreeLevels' => $this->refModel->getAll('degree_levels'),
            'experienceLevels' => $this->refModel->getAll('experience_levels'),
            'workArrangements' => $this->refModel->getAll('work_arrangements'),
        ];
    }

    /**
     * Extract form data from POST
     */
    private function extractFormData()
    {
        $skills = [];
        if (isset($_POST['skills']) && is_array($_POST['skills'])) {
            foreach ($_POST['skills'] as $s) {
                if (!empty($s['skill_id']) && !empty($s['proficiency_level_id'])) {
                    $skills[] = [
                        'skill_id' => intval($s['skill_id']),
                        'proficiency_level_id' => intval($s['proficiency_level_id'])
                    ];
                }
            }
        }

        return [
            'job_title_id' => intval($_POST['job_title_id'] ?? 0),
            'job_category_id' => intval($_POST['job_category_id'] ?? 0),
            'employment_type_id' => intval($_POST['employment_type_id'] ?? 0),
            'industry_id' => intval($_POST['industry_id'] ?? 0),
            'job_level_id' => intval($_POST['job_level_id'] ?? 0),
            'num_openings' => max(1, intval($_POST['num_openings'] ?? 1)),
            'country_id' => intval($_POST['country_id'] ?? 0),
            'city_id' => intval($_POST['city_id'] ?? 0),
            'district_id' => intval($_POST['district_id'] ?? 0),
            'work_arrangement_id' => intval($_POST['work_arrangement_id'] ?? 0),
            'salary_range_id' => intval($_POST['salary_range_id'] ?? 0),
            'salary_type_id' => intval($_POST['salary_type_id'] ?? 0),
            'benefits' => trim($_POST['benefits'] ?? ''),
            'responsibilities' => trim($_POST['responsibilities'] ?? ''),
            'qualifications' => trim($_POST['qualifications'] ?? ''),
            'preferred_skills_text' => trim($_POST['preferred_skills_text'] ?? ''),
            'additional_notes' => trim($_POST['additional_notes'] ?? ''),
            'degree_level_id' => intval($_POST['degree_level_id'] ?? 0),
            'experience_level_id' => intval($_POST['experience_level_id'] ?? 0),
            'skills' => $skills,
            'is_active' => 1
        ];
    }

    /**
     * Validate job data
     */
    private function validateJobData($data)
    {
        $errors = [];

        if (empty($data['job_title_id']))
            $errors['job_title_id'] = 'Job title is required.';
        if (empty($data['job_category_id']))
            $errors['job_category_id'] = 'Job category is required.';
        if (empty($data['employment_type_id']))
            $errors['employment_type_id'] = 'Employment type is required.';
        if (empty($data['industry_id']))
            $errors['industry_id'] = 'Industry is required.';
        if (empty($data['job_level_id']))
            $errors['job_level_id'] = 'Job level is required.';
        if (empty($data['country_id']))
            $errors['country_id'] = 'Country is required.';
        if (empty($data['city_id']))
            $errors['city_id'] = 'City is required.';
        if (empty($data['work_arrangement_id']))
            $errors['work_arrangement_id'] = 'Work arrangement is required.';
        if (empty($data['salary_range_id']))
            $errors['salary_range_id'] = 'Salary range is required.';
        if (empty($data['salary_type_id']))
            $errors['salary_type_id'] = 'Salary type is required.';
        if (empty($data['degree_level_id']))
            $errors['degree_level_id'] = 'Degree level is required.';
        if (empty($data['experience_level_id']))
            $errors['experience_level_id'] = 'Experience level is required.';
        if (empty($data['responsibilities']))
            $errors['responsibilities'] = 'Job responsibilities are required.';
        if (empty($data['qualifications']))
            $errors['qualifications'] = 'Required qualifications are required.';
        if ($data['num_openings'] < 1)
            $errors['num_openings'] = 'Number of openings must be at least 1.';

        // Skills validation (mandatory, max 5)
        if (empty($data['skills'])) {
            $errors['skills'] = 'At least one required skill must be specified.';
        } elseif (count($data['skills']) > MAX_SKILLS_PER_JOB) {
            $errors['skills'] = 'Maximum ' . MAX_SKILLS_PER_JOB . ' skills allowed.';
        }

        return $errors;
    }
}
