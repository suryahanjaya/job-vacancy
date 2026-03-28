<?php
/**
 * Dashboard Controller
 */

require_once APP_ROOT . '/models/JobVacancyModel.php';

class DashboardController
{
    private $jobModel;

    public function __construct()
    {
        $this->jobModel = new JobVacancyModel();
    }

    /**
     * Show dashboard based on role
     */
    public function index()
    {
        $role = getUserRole();

        switch ($role) {
            case 'employer':
                $this->employerDashboard();
                break;
            case 'admin':
                $this->adminDashboard();
                break;
            case 'jobseeker':
            default:
                redirect('/jobs');
                break;
        }
    }

    /**
     * Employer dashboard
     */
    private function employerDashboard()
    {
        $stats = $this->jobModel->getStats(getUserId());
        $recentJobs = $this->jobModel->getByEmployer(getUserId(), 1, 5);

        view('employer/dashboard', [
            'title' => 'Employer Dashboard',
            'stats' => $stats,
            'recentJobs' => $recentJobs['data']
        ]);
    }

    /**
     * Admin dashboard
     */
    private function adminDashboard()
    {
        $stats = $this->jobModel->getStats();

        view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats
        ]);
    }
}
