-- Create database
CREATE DATABASE IF NOT EXISTS userdb;

-- Use the newly created database
USE userdb;

-- Create table for user information
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
