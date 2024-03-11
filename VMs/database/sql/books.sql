CREATE DATABASE IF NOT EXISTS booksdb; 

USE booksdb; 

DROP TABLE IF EXISTS books;

-- Create books table if it does not exist
CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    authors JSON,
    genres JSON,
    languages VARCHAR(10) NOT NULL,
    year_published INT,
    description TEXT,
    cover_image_url VARCHAR(255) DEFAULT '/book_covers/default.png',
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);