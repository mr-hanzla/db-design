-- query to show currently in-use DB
select current_database();

-- COMPANY table
CREATE TABLE IF NOT EXISTS company (
	company_id INT PRIMARY KEY NOT NULL,
	company_name VARCHAR(50) NOT NULL,
	company_logo VARCHAR(400),
	company_description TEXT NOT NULL
);

-- DEPARTMENT table
CREATE TABLE IF NOT EXISTS department (
	department_id INT PRIMARY KEY,
	department_name VARCHAR(30) NOT NULL
);

-- EMPLOYEE table
CREATE TABLE IF NOT EXISTS employee (
	employee_id INT PRIMARY KEY,
	employee_name VARCHAR(60) NOT NULL,
	department_id INT REFERENCES department
);
