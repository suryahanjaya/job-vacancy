-- =============================================
-- Seed Data for Reference Tables
-- =============================================

USE job_vacancy_system;

-- Job Categories
INSERT INTO job_categories (name) VALUES
('Software Engineering'), ('Data Science & Analytics'), ('Product Management'),
('Design & UX'), ('Marketing & Communications'), ('Finance & Accounting'),
('Human Resources'), ('Operations & Logistics'), ('Sales & Business Development'),
('Customer Support'), ('Legal & Compliance'), ('Healthcare & Medical'),
('Education & Training'), ('Engineering & Manufacturing'), ('Media & Entertainment');

-- Job Titles
INSERT INTO job_titles (name, category_id) VALUES
('Software Engineer', 1), ('Frontend Developer', 1), ('Backend Developer', 1),
('Full Stack Developer', 1), ('Mobile Developer', 1), ('DevOps Engineer', 1),
('QA Engineer', 1), ('Data Scientist', 2), ('Data Analyst', 2),
('Data Engineer', 2), ('Machine Learning Engineer', 2), ('Product Manager', 3),
('Product Owner', 3), ('Business Analyst', 3), ('UI Designer', 4),
('UX Designer', 4), ('UX Researcher', 4), ('Graphic Designer', 4),
('Marketing Manager', 5), ('Content Strategist', 5), ('SEO Specialist', 5),
('Financial Analyst', 6), ('Accountant', 6), ('HR Manager', 7),
('Recruiter', 7), ('Project Manager', 8), ('Operations Manager', 8),
('Sales Representative', 9), ('Account Executive', 9), ('Customer Service Rep', 10),
('Legal Counsel', 11), ('Compliance Officer', 11), ('Physician', 12),
('Nurse', 12), ('Teacher', 13), ('Training Specialist', 13),
('Mechanical Engineer', 14), ('Electrical Engineer', 14), ('Video Producer', 15),
('Content Creator', 15), ('System Administrator', 1), ('Cloud Architect', 1),
('Security Engineer', 1), ('Database Administrator', 1), ('Technical Lead', 1);

-- Employment Types
INSERT INTO employment_types (name) VALUES
('Full-time'), ('Part-time'), ('Contract'), ('Internship'), ('Freelance'), ('Temporary');

-- Industries
INSERT INTO industries (name) VALUES
('Information Technology'), ('Finance & Banking'), ('Healthcare & Pharmaceuticals'),
('Education'), ('E-commerce & Retail'), ('Manufacturing'), ('Telecommunications'),
('Media & Entertainment'), ('Real Estate'), ('Automotive'),
('Energy & Utilities'), ('Government & Public Sector'), ('Consulting'),
('Transportation & Logistics'), ('Food & Beverage'), ('Agriculture'),
('Non-profit & NGO'), ('Insurance'), ('Legal Services'), ('Hospitality & Tourism');

-- Job Levels
INSERT INTO job_levels (name) VALUES
('Intern'), ('Junior'), ('Mid'), ('Senior'), ('Lead'), ('Manager'), ('Director'), ('Executive');

-- Salary Ranges
INSERT INTO salary_ranges (label, min_salary, max_salary, currency) VALUES
('Under 500 USD', 0, 500, 'USD'),
('500 - 1,000 USD', 500, 1000, 'USD'),
('1,000 - 1,500 USD', 1000, 1500, 'USD'),
('1,500 - 2,000 USD', 1500, 2000, 'USD'),
('2,000 - 3,000 USD', 2000, 3000, 'USD'),
('3,000 - 5,000 USD', 3000, 5000, 'USD'),
('5,000 - 7,000 USD', 5000, 7000, 'USD'),
('7,000 - 10,000 USD', 7000, 10000, 'USD'),
('10,000 - 15,000 USD', 10000, 15000, 'USD'),
('Over 15,000 USD', 15000, 99999, 'USD'),
('Negotiable', 0, 0, 'USD');

-- Salary Types
INSERT INTO salary_types (name) VALUES ('Gross'), ('Net');

-- Skills
INSERT INTO skills (name) VALUES
('PHP'), ('JavaScript'), ('Python'), ('Java'), ('C#'), ('C++'),
('TypeScript'), ('Ruby'), ('Go'), ('Rust'), ('Swift'), ('Kotlin'),
('HTML/CSS'), ('React'), ('Angular'), ('Vue.js'), ('Node.js'), ('Django'),
('Laravel'), ('Spring Boot'), ('ASP.NET'), ('Express.js'), ('Flask'),
('SQL'), ('MySQL'), ('PostgreSQL'), ('MongoDB'), ('Redis'),
('Docker'), ('Kubernetes'), ('AWS'), ('Azure'), ('Google Cloud'),
('Git'), ('CI/CD'), ('Linux'), ('REST API'), ('GraphQL'),
('Machine Learning'), ('Deep Learning'), ('TensorFlow'), ('PyTorch'),
('Data Visualization'), ('Tableau'), ('Power BI'), ('Excel'),
('Agile/Scrum'), ('Project Management'), ('Communication'),
('Problem Solving'), ('Leadership'), ('Teamwork'),
('Adobe Photoshop'), ('Adobe Illustrator'), ('Figma'), ('Sketch'),
('SEO'), ('Google Analytics'), ('Content Writing'),
('Salesforce'), ('SAP'), ('Jira'), ('Confluence');

-- Proficiency Levels
INSERT INTO proficiency_levels (name, level_order) VALUES
('Beginner', 1), ('Elementary', 2), ('Intermediate', 3),
('Advanced', 4), ('Expert', 5);

-- Countries
INSERT INTO countries (name, code) VALUES
('Vietnam', 'VN'), ('United States', 'US'), ('United Kingdom', 'GB'),
('Singapore', 'SG'), ('Japan', 'JP'), ('South Korea', 'KR'),
('Australia', 'AU'), ('Germany', 'DE'), ('Canada', 'CA'),
('France', 'FR'), ('India', 'IN'), ('China', 'CN'),
('Thailand', 'TH'), ('Malaysia', 'MY'), ('Indonesia', 'ID');

-- Cities (Vietnam)
INSERT INTO cities (name, country_id) VALUES
('Ho Chi Minh City', 1), ('Hanoi', 1), ('Da Nang', 1),
('Can Tho', 1), ('Hai Phong', 1), ('Nha Trang', 1),
('Hue', 1), ('Bien Hoa', 1), ('Vung Tau', 1);

-- Cities (USA)
INSERT INTO cities (name, country_id) VALUES
('New York', 2), ('San Francisco', 2), ('Los Angeles', 2),
('Seattle', 2), ('Chicago', 2), ('Austin', 2), ('Boston', 2);

-- Cities (Singapore)
INSERT INTO cities (name, country_id) VALUES ('Singapore', 4);

-- Cities (Japan)
INSERT INTO cities (name, country_id) VALUES ('Tokyo', 5), ('Osaka', 5);

-- Districts (Ho Chi Minh City)
INSERT INTO districts (name, city_id) VALUES
('District 1', 1), ('District 2 (Thu Duc City)', 1), ('District 3', 1),
('District 4', 1), ('District 5', 1), ('District 7', 1),
('District 9 (Thu Duc City)', 1), ('Binh Thanh', 1), ('Tan Binh', 1),
('Phu Nhuan', 1), ('Go Vap', 1), ('Thu Duc City', 1);

-- Districts (Hanoi)
INSERT INTO districts (name, city_id) VALUES
('Ba Dinh', 2), ('Hoan Kiem', 2), ('Dong Da', 2),
('Hai Ba Trung', 2), ('Cau Giay', 2), ('Nam Tu Liem', 2);

-- Degree Levels
INSERT INTO degree_levels (name, level_order) VALUES
('No Requirement', 0), ('High School', 1), ('Associate', 2),
('Bachelor', 3), ('Master', 4), ('PhD', 5), ('Professional Certificate', 6);

-- Experience Levels
INSERT INTO experience_levels (label, min_years, max_years) VALUES
('No Experience', 0, 0), ('Less than 1 year', 0, 1),
('1 - 2 years', 1, 2), ('2 - 3 years', 2, 3),
('3 - 5 years', 3, 5), ('5 - 7 years', 5, 7),
('7 - 10 years', 7, 10), ('Over 10 years', 10, NULL);

-- Work Arrangements
INSERT INTO work_arrangements (name) VALUES
('Onsite'), ('Remote'), ('Hybrid');

-- Default Admin User (password: admin123)
INSERT INTO users (email, password, full_name, role) VALUES
('admin@jobsystem.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin');

-- Sample Employer (password: employer123)
INSERT INTO users (email, password, full_name, company_name, role) VALUES
('employer@techcorp.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Smith', 'TechCorp Solutions', 'employer');

-- Sample Job Seeker (password: seeker123)
INSERT INTO users (email, password, full_name, role) VALUES
('seeker@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Doe', 'jobseeker');
