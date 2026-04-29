<?php
/**
 * Admin Controller
 */

require_once APP_ROOT . '/models/JobVacancyModel.php';
require_once APP_ROOT . '/models/ReferenceModel.php';
require_once APP_ROOT . '/models/UserModel.php';

class AdminController
{
    private $jobModel;
    private $refModel;
    private $userModel;

    public function __construct()
    {
        $this->jobModel = new JobVacancyModel();
        $this->refModel = new ReferenceModel();
        $this->userModel = new UserModel();
    }

    /**
     * Manage job vacancies
     */
    public function jobs()
    {
        $page = max(1, intval($_GET['page'] ?? 1));
        $result = $this->jobModel->getAll($page);

        view('admin/jobs', [
            'title' => 'Manage Job Postings',
            'jobs' => $result['data'],
            'pagination' => $result
        ]);
    }

    /**
     * Toggle job status (admin)
     */
    public function toggleJob($id)
    {
        $this->jobModel->toggleActive($id);
        setFlash('success', 'Job posting status updated.');
        redirect('/admin/jobs');
    }

    /**
     * Delete job (admin)
     */
    public function deleteJob($id)
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect('/admin/jobs');
            return;
        }

        $this->jobModel->delete($id);
        setFlash('success', 'Job posting removed.');
        redirect('/admin/jobs');
    }

    /**
     * List reference table entries
     */
    public function referenceList($table)
    {
        $allowedTables = [
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

        if (!isset($allowedTables[$table])) {
            setFlash('error', 'Invalid reference table.');
            redirect('/dashboard');
            return;
        }

        $items = $this->refModel->getAll($table);

        view('admin/reference-list', [
            'title' => 'Manage ' . $allowedTables[$table],
            'tableName' => $table,
            'tableLabel' => $allowedTables[$table],
            'items' => $items,
            'allowedTables' => $allowedTables
        ]);
    }

    /**
     * Create reference entry
     */
    public function referenceCreate($table)
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect("/admin/reference/$table");
            return;
        }

        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            setFlash('error', 'Name is required.');
            redirect("/admin/reference/$table");
            return;
        }

        try {
            $data = ['name' => $name];

            // Handle special fields for different tables
            if ($table === 'job_titles' && !empty($_POST['category_id'])) {
                $data['category_id'] = intval($_POST['category_id']);
            }
            if ($table === 'cities' && !empty($_POST['country_id'])) {
                $data['country_id'] = intval($_POST['country_id']);
            }
            if ($table === 'districts' && !empty($_POST['city_id'])) {
                $data['city_id'] = intval($_POST['city_id']);
            }
            if ($table === 'salary_ranges') {
                $data['label'] = $name;
                $data['min_salary'] = floatval($_POST['min_salary'] ?? 0);
                $data['max_salary'] = floatval($_POST['max_salary'] ?? 0);
                unset($data['name']);
            }
            if ($table === 'experience_levels') {
                $data['label'] = $name;
                $data['min_years'] = intval($_POST['min_years'] ?? 0);
                $data['max_years'] = !empty($_POST['max_years']) ? intval($_POST['max_years']) : null;
                unset($data['name']);
            }
            if (in_array($table, ['proficiency_levels', 'degree_levels'])) {
                $data['level_order'] = intval($_POST['level_order'] ?? 0);
            }
            if ($table === 'countries') {
                $data['code'] = strtoupper(trim($_POST['code'] ?? ''));
            }

            $this->refModel->create($table, $data);
            setFlash('success', 'Entry added successfully.');
        } catch (Exception $e) {
            setFlash('error', 'Failed to create entry. It may already exist.');
        }

        redirect("/admin/reference/$table");
    }

    /**
     * Delete reference entry
     */
    public function referenceDelete($table, $id)
    {
        if (!verifyCsrf()) {
            setFlash('error', 'Invalid request.');
            redirect("/admin/reference/$table");
            return;
        }

        try {
            $this->refModel->delete($table, $id);
            setFlash('success', 'Entry removed successfully.');
        } catch (Exception $e) {
            setFlash('error', 'Cannot delete this entry. It may be in use by job postings.');
        }

        redirect("/admin/reference/$table");
    }

    /**
     * Manage users
     */
    public function users()
    {
        $users = $this->userModel->getAll();

        view('admin/users', [
            'title' => 'Manage Users',
            'users' => $users
        ]);
    }

    /**
     * Toggle user active status
     */
    public function toggleUser($id)
    {
        if ($id == getUserId()) {
            setFlash('error', 'You cannot deactivate your own account.');
        } else {
            $this->userModel->toggleActive($id);
            setFlash('success', 'User status updated.');
        }
        redirect('/admin/users');
    }
}
