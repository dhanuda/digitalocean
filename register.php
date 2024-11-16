<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from AJAX request
$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$files = json_encode($_POST['files']); // Store file URLs as JSON

$sql = "INSERT INTO users (name, email, password, files) VALUES ('$name', '$email', '$password', '$files')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['message' => 'Registration successful!']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error: ' . $conn->error]);
}

$conn->close();
?>
