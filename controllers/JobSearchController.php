<?php
/**
 * Job Search Controller (Job Seeker)
 */

require_once APP_ROOT . '/models/JobVacancyModel.php';
require_once APP_ROOT . '/models/ReferenceModel.php';

class JobSearchController
{
    private $jobModel;
    private $refModel;

    public function __construct()
    {
        $this->jobModel = new JobVacancyModel();
        $this->refModel = new ReferenceModel();
    }

    /**
     * Search & list jobs
     */
    public function index()
    {
        $filters = [
            'keyword' => trim($_GET['keyword'] ?? ''),
            'job_category_id' => intval($_GET['job_category_id'] ?? 0),
            'country_id' => intval($_GET['country_id'] ?? 0),
            'city_id' => intval($_GET['city_id'] ?? 0),
            'employment_type_id' => intval($_GET['employment_type_id'] ?? 0),
            'job_level_id' => intval($_GET['job_level_id'] ?? 0),
            'salary_range_id' => intval($_GET['salary_range_id'] ?? 0),
            'work_arrangement_id' => intval($_GET['work_arrangement_id'] ?? 0),
            'skill_id' => intval($_GET['skill_id'] ?? 0),
            'industry_id' => intval($_GET['industry_id'] ?? 0),
        ];

        // Remove empty filters
        $activeFilters = array_filter($filters);

        $sort = $_GET['sort'] ?? 'newest';
        $page = max(1, intval($_GET['page'] ?? 1));

        $result = $this->jobModel->search($activeFilters, $page, ITEMS_PER_PAGE, $sort);

        // Load reference data for filter dropdowns
        $referenceData = [
            'categories' => $this->refModel->getAll('job_categories'),
            'employmentTypes' => $this->refModel->getAll('employment_types'),
            'jobLevels' => $this->refModel->getAll('job_levels'),
            'salaryRanges' => $this->refModel->getAll('salary_ranges'),
            'countries' => $this->refModel->getAll('countries'),
            'cities' => $this->refModel->getAll('cities'),
            'skills' => $this->refModel->getAll('skills'),
            'industries' => $this->refModel->getAll('industries'),
            'workArrangements' => $this->refModel->getAll('work_arrangements'),
        ];

        view('jobseeker/search', array_merge([
            'title' => 'Find Jobs',
            'jobs' => $result['data'],
            'pagination' => $result,
            'filters' => $filters,
            'sort' => $sort,
        ], $referenceData));
    }

    /**
     * View single job
     */
    public function show($id)
    {
        $job = $this->jobModel->findById($id);

        if (!$job || !$job['is_active']) {
            setFlash('error', 'Job posting not found.');
            redirect('/jobs');
            return;
        }

        view('jobseeker/job-detail', [
            'title' => $job['job_title_name'] . ' at ' . ($job['company_name'] ?? $job['employer_name']),
            'job' => $job
        ]);
    }
}
