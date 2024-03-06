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

-- Create user 'guest' if it doesn't already exist, with password '3394dzwHi0HJimrA13JO' and grant access only from localhost
CREATE USER IF NOT EXISTS 'guest'@'localhost' IDENTIFIED BY '3394dzwHi0HJimrA13JO';

-- Grant SELECT and INSERT permissions to user 'guest' on table 'users' in database 'userdb'
GRANT SELECT, INSERT ON userdb.* TO 'guest'@'localhost';