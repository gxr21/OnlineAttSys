<?php
try {
    $dsn = 'mysql:host=localhost;dbname=attendance_db;charset=utf8';
    $username = 'root'; 
    $password = '';

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $usersTable = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('admin', 'teacher') NOT NULL
    )";
    $pdo->exec($usersTable);

    $studentsTable = "
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(255) NOT NULL,
        middle_name VARCHAR(255),
        last_name VARCHAR(255) NOT NULL,
        stage ENUM('1', '2', '3', '4') NOT NULL
    )";
    $pdo->exec($studentsTable);

    $subjectsTable = "
    CREATE TABLE IF NOT EXISTS subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        stage ENUM('1', '2', '3', '4') NOT NULL,
        total_hours INT NOT NULL
    )";
    $pdo->exec($subjectsTable);

    $absencesTable = "
    CREATE TABLE IF NOT EXISTS absences (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        subject_id INT NOT NULL,
        absence_date DATE NOT NULL,
        absence_hours INT NOT NULL,
        FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
        FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
    )";
    $pdo->exec($absencesTable);

    echo "Tables created successfully.";
} catch (PDOException $e) {
    die("Error creating tables: " . $e->getMessage());
}
?>
