CREATE DATABASE complaints_db
    DEFAULT CHARACTER SET = 'utf8mb4';

USE complaints_db;

CREATE TABLE complaints_db.complaints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

ALTER TABLE complaints
ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL;
