<?php
/**
 * Home Controller
 */

require_once APP_ROOT . '/models/JobVacancyModel.php';
require_once APP_ROOT . '/models/ReferenceModel.php';

class HomeController
{
    public function index()
    {
        $jobModel = new JobVacancyModel();
        $refModel = new ReferenceModel();

        $latestJobs = $jobModel->search([], 1, 6, 'newest');
        $stats = $jobModel->getStats();

        view('home', [
            'title' => 'Find Your Dream Job',
            'latestJobs' => $latestJobs['data'],
            'totalJobs' => $latestJobs['total'],
            'categories' => $refModel->getAll('job_categories'),
            'stats' => $stats
        ]);
    }
}
