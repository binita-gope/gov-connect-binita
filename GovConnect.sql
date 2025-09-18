-- Create Database
CREATE DATABASE IF NOT EXISTS gov_response_system;
USE gov_response_system;

-- Users Table (normal users, admin, response team)
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin','response') NOT NULL DEFAULT 'user',
    location VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Problems Table
CREATE TABLE problems (
    problem_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category ENUM('fire','robbery','road','medical','other') NOT NULL,
    description TEXT,
    location VARCHAR(255) NOT NULL,
    status ENUM('pending','verified','assigned','resolved','rejected') DEFAULT 'pending',
    priority ENUM('low','medium','high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Responses Table (who handled the problem)
CREATE TABLE responses (
    response_id INT AUTO_INCREMENT PRIMARY KEY,
    problem_id INT NOT NULL,
    responder_id INT NOT NULL,
    response_action TEXT,
    notified BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (problem_id) REFERENCES problems(problem_id) ON DELETE CASCADE,
    FOREIGN KEY (responder_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Logs Table (emails, sms, calls, etc.)
CREATE TABLE logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    problem_id INT NOT NULL,
    notification_type ENUM('email','sms','call') NOT NULL,
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (problem_id) REFERENCES problems(problem_id) ON DELETE CASCADE
);




SHOW DATABASES;
USE gov_response_system;
SHOW TABLES;




-- Insert an Admin
INSERT INTO users (name, email, phone, password, role, location) 
VALUES ('System Admin', 'admin@gov.com', '01700000000', 'admin123', 'admin', 'Dhaka');

-- Insert a Normal User
INSERT INTO users (name, email, phone, password, role, location) 
VALUES ('Rahim', 'rahim@gmail.com', '01811111111', 'rahim123', 'user', 'Khilkhet, Dhaka');

-- Insert a Response Team Member (Police)
INSERT INTO users (name, email, phone, password, role, location) 
VALUES ('OC Banani Police', 'banani.police@gmail.com', '01922222222', 'police123', 'response', 'Banani, Dhaka');

-- Insert a Test Problem
INSERT INTO problems (user_id, category, description, location, priority)
VALUES (2, 'fire', 'House is on fire near Khilkhet bridge', 'Khilkhet, Dhaka', 'high');

SELECT * FROM users;
SELECT * FROM problems;

-- Update admin password to hashed version
UPDATE users SET password = '$2y$10$zQW1N4SoSkpsYVmmn3bMG.q8Q3ibcA0EuHBhmtAxSW8v9BN8Vh7Eq' WHERE email='admin@gov.com';
-- password = admin123

-- Update user password
UPDATE users SET password = '$2y$10$8rTj2Y2P1F3aWbDhpvDjuOVhV7Ai13EepOW0Z9zUzGCBVtZW3I0hK' WHERE email='rahim@gmail.com';
-- password = rahim123

-- Update response password
UPDATE users SET password = '$2y$10$Y6oGmYBp0mJZBL5OxI6F7u.L8dAfkqDMEVn4xiLulc9sTk/UsD0xG' WHERE email='banani.police@gmail.com';
-- password = police123



select * from problems;

ALTER TABLE problems
ADD COLUMN suggestion VARCHAR(255) DEFAULT NULL;


truncate table problems ;

