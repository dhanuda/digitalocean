CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    files TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
