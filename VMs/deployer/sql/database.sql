-- Create database
CREATE DATABASE IF NOT EXISTS bookShelf;

-- Use the newly created database
USE bookShelf;

DROP TABLE IF EXISTS packages;

-- Create table packages
CREATE TABLE IF NOT EXISTS packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    version VARCHAR(50),
    stage VARCHAR(50) NOT NULL,
    installation_flags TEXT,
    file_location VARCHAR(255) NOT NULL,
    status ENUM('good', 'bad') DEFAULT 'good'
);
