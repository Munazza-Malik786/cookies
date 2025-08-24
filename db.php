<?php
// Database connection details
$servername = "localhost";   // Hosting ka database host
$username   = "root";        // Apna database username
$password   = "";            // Apna database password
$dbname     = "cookies";     // Apna database name

// Connect to MySQL
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150) UNIQUE,
    picture VARCHAR(255),
    location VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Get JSON data from frontend
$input = json_decode(file_get_contents("php://input"), true);

if ($input) {
    $name     = $conn->real_escape_string($input['name']);
    $email    = $conn->real_escape_string($input['email']);
    $picture  = $conn->real_escape_string($input['picture']);
    $location = $conn->real_escape_string($input['location']);

    // Insert or Update user data
    $sql = "INSERT INTO data (name, email, picture, location) 
            VALUES ('$name', '$email', '$picture', '$location')
            ON DUPLICATE KEY UPDATE 
            name='$name', picture='$picture', location='$location'";

    if ($conn->query($sql) === TRUE) {
        echo "User data saved successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
