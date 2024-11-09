CREATE DATABASE IF NOT EXISTS ZAI;
USE ZAI;

CREATE TABLE CATEGORY (
    id INT AUTO_INCREMENT PRIMARY KEY,
    color VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE USER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE ENTRY (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    image_url VARCHAR(255),
    category INT,
    user INT,
    FOREIGN KEY (category) REFERENCES CATEGORY(id),
    FOREIGN KEY (user) REFERENCES USER(id)
);

-- Add test data
INSERT INTO CATEGORY (color, type) VALUES 
    ('#FF5733', 'Warning'),
    ('#33FF57', 'Information'),
    ('#3357FF', 'Important');

INSERT INTO USER (login, password) VALUES 
    ('user', 'hashed_password_456'),
    ('admin', 'hashed_admin_password');

INSERT INTO ENTRY (title, description, start_date, end_date, image_url, category, user) VALUES
    ('Meeting with the team', 'Next week we are planning a very important meeting, be prepared!', '2024-11-07', '2024-11-07', 'https://img.freepik.com/free-photo/colorful-design-with-spiral-design_188544-9588.jpg', 3, 1),
    ('Vacation', 'I visited my family last weekend. It was beatiful, we had great time and I finally found some rest.', '2024-11-08', '2024-11-08', 'https://images.pexels.com/photos/414612/pexels-photo-414612.jpeg', 2, 2),
    ('Maintenance window', 'Website will be unavailable for the next few hours.', '2024-11-05', '2024-11-05', '', 3, 2),
    ('Bye w3schools ', 'w3schools website will be taken down.', '2024-11-10', '2024-11-12', 'https://www.w3schools.com/images/w3chools_green.jpg', 1, 1);
