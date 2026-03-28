<?php
/**
 * Reference Data Model
 * Handles all lookup/reference tables
 */

class ReferenceModel
{
    private $db;

    // Allowed reference tables (whitelist for security)
    private $allowedTables = [
        'job_categories',
        'job_titles',
        'employment_types',
        'industries',
        'job_levels',
        'salary_ranges',
        'salary_types',
        'skills',
        'proficiency_levels',
        'countries',
        'cities',
        'districts',
        'degree_levels',
        'experience_levels',
        'work_arrangements'
    ];

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get all records from a reference table
     */
    public function getAll($table, $orderBy = 'name')
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        // Special ordering for some tables
        $orderColumn = $orderBy;
        if (in_array($table, ['proficiency_levels', 'degree_levels'])) {
            $orderColumn = 'level_order';
        } elseif ($table === 'salary_ranges') {
            $orderColumn = 'min_salary';
        } elseif ($table === 'experience_levels') {
            $orderColumn = 'min_years';
        }

        $stmt = $this->db->query("SELECT * FROM `$table` ORDER BY `$orderColumn` ASC");
        return $stmt->fetchAll();
    }

    /**
     * Get a single record by ID
     */
    public function getById($table, $id)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        $stmt = $this->db->prepare("SELECT * FROM `$table` WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create a record in a reference table
     */
    public function create($table, $data)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        $columns = array_keys($data);
        $placeholders = array_fill(0, count($data), '?');

        $sql = "INSERT INTO `$table` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return $this->db->lastInsertId();
    }

    /**
     * Update a record in a reference table
     */
    public function update($table, $id, $data)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        $sets = [];
        $values = [];
        foreach ($data as $col => $val) {
            $sets[] = "`$col` = ?";
            $values[] = $val;
        }
        $values[] = $id;

        $sql = "UPDATE `$table` SET " . implode(', ', $sets) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Delete from reference table
     */
    public function delete($table, $id)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        $stmt = $this->db->prepare("DELETE FROM `$table` WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get cities by country
     */
    public function getCitiesByCountry($countryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM cities WHERE country_id = ? ORDER BY name ASC");
        $stmt->execute([$countryId]);
        return $stmt->fetchAll();
    }

    /**
     * Get districts by city
     */
    public function getDistrictsByCity($cityId)
    {
        $stmt = $this->db->prepare("SELECT * FROM districts WHERE city_id = ? ORDER BY name ASC");
        $stmt->execute([$cityId]);
        return $stmt->fetchAll();
    }

    /**
     * Get job titles by category
     */
    public function getJobTitlesByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM job_titles WHERE category_id = ? ORDER BY name ASC");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    /**
     * Count records in a table
     */
    public function count($table)
    {
        if (!in_array($table, $this->allowedTables)) {
            throw new Exception("Invalid reference table: $table");
        }

        return $this->db->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
    }
}
