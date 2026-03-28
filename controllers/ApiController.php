<?php
/**
 * API Controller for AJAX requests
 * Handles dynamic dropdown data (cascading selects)
 */

require_once APP_ROOT . '/models/ReferenceModel.php';

class ApiController
{
    private $refModel;

    public function __construct()
    {
        $this->refModel = new ReferenceModel();
    }

    /**
     * Get cities by country (AJAX)
     */
    public function getCities()
    {
        header('Content-Type: application/json');
        $countryId = intval($_GET['country_id'] ?? 0);

        if ($countryId <= 0) {
            echo json_encode([]);
            return;
        }

        $cities = $this->refModel->getCitiesByCountry($countryId);
        echo json_encode($cities);
    }

    /**
     * Get districts by city (AJAX)
     */
    public function getDistricts()
    {
        header('Content-Type: application/json');
        $cityId = intval($_GET['city_id'] ?? 0);

        if ($cityId <= 0) {
            echo json_encode([]);
            return;
        }

        $districts = $this->refModel->getDistrictsByCity($cityId);
        echo json_encode($districts);
    }

    /**
     * Get job titles by category (AJAX)
     */
    public function getJobTitles()
    {
        header('Content-Type: application/json');
        $categoryId = intval($_GET['category_id'] ?? 0);

        if ($categoryId <= 0) {
            echo json_encode([]);
            return;
        }

        $titles = $this->refModel->getJobTitlesByCategory($categoryId);
        echo json_encode($titles);
    }
}
