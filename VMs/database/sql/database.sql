-- Create database
CREATE DATABASE IF NOT EXISTS bookShelf;

-- Use the newly created database
USE bookShelf;

DROP TABLE IF EXISTS discussions;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS user_library;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;


-- Create table for user information
CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
);

CREATE TABLE IF NOT EXISTS books (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    authors JSON,
    genres JSON,
    languages VARCHAR(10) NOT NULL,
    year_published INT,
    description TEXT,
    cover_image_url VARCHAR(255) DEFAULT '/book_covers/default.jpg',
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS sessions (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sessionId VARCHAR(255) NOT NULL UNIQUE,
    userId INT(6) UNSIGNED NOT NULL UNIQUE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    expired_at TIMESTAMP NOT NULL DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    FOREIGN KEY (userId) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS user_library ( 
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED,
    book_id INT,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE IF NOT EXISTS discussions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    user_id INT(6) UNSIGNED,
    username VARCHAR(50),
    comment TEXT,
    reply_to_id INT UNSIGNED,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_reply_to_id FOREIGN KEY (reply_to_id) REFERENCES discussions(id)
);

CREATE TABLE IF NOT EXISTS reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    user_id INT(6) UNSIGNED,
    username VARCHAR(50),
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)
);

CREATE TABLE IF NOT EXISTS updates (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT(6) UNSIGNED NOT NULL,
    type ENUM('author', 'genre', 'book') NOT NULL,
    query VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);