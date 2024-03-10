CREATE DATABASE IF NOT EXISTS booksdb; 

USE booksdb; 

-- Create authors table if it does not exist
CREATE TABLE IF NOT EXISTS authors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

-- Create genres table if it does not exist
CREATE TABLE IF NOT EXISTS genres (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL
);

-- Create languages table if it does not exist
CREATE TABLE IF NOT EXISTS languages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

-- Create books table if it does not exist
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    authors JSON,
    genres JSON,
    languages JSON,
    year_published INT,
    description TEXT,
    isbn VARCHAR(20),
    cover_image_url VARCHAR(255),
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);