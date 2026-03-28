-- =============================================
-- Job Vacancy Management & Job Search System
-- Database Schema
-- =============================================

CREATE DATABASE IF NOT EXISTS job_vacancy_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE job_vacancy_system;

-- =============================================
-- REFERENCE (LOOKUP) TABLES
-- =============================================

CREATE TABLE job_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE job_titles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES job_categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE employment_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE industries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE job_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE salary_ranges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL UNIQUE,
    min_salary DECIMAL(12,2) NOT NULL,
    max_salary DECIMAL(12,2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'USD',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE salary_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE proficiency_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    level_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    code VARCHAR(5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE cities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    country_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE districts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE degree_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    level_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE experience_levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(50) NOT NULL UNIQUE,
    min_years INT NOT NULL DEFAULT 0,
    max_years INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE work_arrangements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- =============================================
-- MAIN TABLES
-- =============================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    company_name VARCHAR(200) DEFAULT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role ENUM('employer', 'jobseeker', 'admin') NOT NULL DEFAULT 'jobseeker',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE job_vacancies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employer_id INT NOT NULL,
    job_title_id INT NOT NULL,
    job_category_id INT NOT NULL,
    employment_type_id INT NOT NULL,
    industry_id INT NOT NULL,
    job_level_id INT NOT NULL,
    num_openings INT NOT NULL DEFAULT 1,
    
    -- Location (structured)
    country_id INT NOT NULL,
    city_id INT NOT NULL,
    district_id INT DEFAULT NULL,
    work_arrangement_id INT NOT NULL,
    
    -- Salary
    salary_range_id INT NOT NULL,
    salary_type_id INT NOT NULL,
    benefits TEXT DEFAULT NULL,
    
    -- Description (free text)
    responsibilities TEXT NOT NULL,
    qualifications TEXT NOT NULL,
    preferred_skills_text TEXT DEFAULT NULL,
    additional_notes TEXT DEFAULT NULL,
    
    -- Education & Experience
    degree_level_id INT NOT NULL,
    experience_level_id INT NOT NULL,
    
    -- Status
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign Keys
    FOREIGN KEY (employer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_title_id) REFERENCES job_titles(id) ON DELETE RESTRICT,
    FOREIGN KEY (job_category_id) REFERENCES job_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (employment_type_id) REFERENCES employment_types(id) ON DELETE RESTRICT,
    FOREIGN KEY (industry_id) REFERENCES industries(id) ON DELETE RESTRICT,
    FOREIGN KEY (job_level_id) REFERENCES job_levels(id) ON DELETE RESTRICT,
    FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE RESTRICT,
    FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE RESTRICT,
    FOREIGN KEY (district_id) REFERENCES districts(id) ON DELETE SET NULL,
    FOREIGN KEY (work_arrangement_id) REFERENCES work_arrangements(id) ON DELETE RESTRICT,
    FOREIGN KEY (salary_range_id) REFERENCES salary_ranges(id) ON DELETE RESTRICT,
    FOREIGN KEY (salary_type_id) REFERENCES salary_types(id) ON DELETE RESTRICT,
    FOREIGN KEY (degree_level_id) REFERENCES degree_levels(id) ON DELETE RESTRICT,
    FOREIGN KEY (experience_level_id) REFERENCES experience_levels(id) ON DELETE RESTRICT,
    
    -- Indexes for search
    INDEX idx_employer (employer_id),
    INDEX idx_category (job_category_id),
    INDEX idx_level (job_level_id),
    INDEX idx_location (country_id, city_id),
    INDEX idx_active (is_active),
    INDEX idx_created (created_at),
    FULLTEXT INDEX idx_fulltext_desc (responsibilities, qualifications, preferred_skills_text, additional_notes)
) ENGINE=InnoDB;

-- Many-to-Many: Job Vacancies <-> Skills
CREATE TABLE job_vacancy_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_vacancy_id INT NOT NULL,
    skill_id INT NOT NULL,
    proficiency_level_id INT NOT NULL,
    FOREIGN KEY (job_vacancy_id) REFERENCES job_vacancies(id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE,
    FOREIGN KEY (proficiency_level_id) REFERENCES proficiency_levels(id) ON DELETE RESTRICT,
    UNIQUE KEY unique_job_skill (job_vacancy_id, skill_id)
) ENGINE=InnoDB;
