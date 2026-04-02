-- Create Database
CREATE DATABASE IF NOT EXISTS pulsefit;
USE pulsefit;

-- =========================
-- USERS TABLE
-- =========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================
-- WORKOUTS TABLE
-- =========================
CREATE TABLE workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(100),
    activity VARCHAR(100),
    duration INT,
    calories INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE
);

-- =========================
-- GOALS TABLE
-- =========================
CREATE TABLE goals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(100),
    target_calories INT,
    target_workouts INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE
);

-- =========================
-- HEALTH TABLE
-- =========================
CREATE TABLE health (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(100),
    heart_rate INT,
    weight FLOAT,
    steps INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(username) ON DELETE CASCADE
);