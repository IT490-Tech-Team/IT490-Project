-- Create database
CREATE DATABASE IF NOT EXISTS userdb;

-- Use the newly created database
USE userdb;

DROP TABLE IF EXISTS users;


-- Create table for user information
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS sessions;

CREATE TABLE IF NOT EXISTS sessions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sessionId VARCHAR(255) NOT NULL UNIQUE,
    userId INT(6) UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expired_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    FOREIGN KEY (userId) REFERENCES users(id)
);
