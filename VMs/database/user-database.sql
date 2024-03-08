-- Create database
CREATE DATABASE IF NOT EXISTS userdb;

-- Use the newly created database
USE userdb;

-- Create table for user information
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS sessions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sessionId VARCHAR(255) NOT NULL UNIQUE,
    userId INT(6) UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expired_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    FOREIGN KEY (userId) REFERENCES users(id)
);

-- Create user 'guest' if it doesn't already exist, with password '3394dzwHi0HJimrA13JO' and grant access only from localhost
CREATE USER IF NOT EXISTS 'guest'@'localhost' IDENTIFIED BY '3394dzwHi0HJimrA13JO';

-- Grant SELECT and INSERT permissions to user 'guest' on table 'users' in database 'userdb'
GRANT SELECT, INSERT, DELETE ON userdb.* TO 'guest'@'localhost';