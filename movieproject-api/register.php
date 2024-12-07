<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$host = 'localhost';  
$dbname = 'movieprojectdb'; 
$username = 'root'; 
$password = ''; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));  // Handle DB connection error
}

$data = json_decode(file_get_contents("php://input"), true);  // Get JSON data from request

if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT); 
$firstName = $data['firstName'];
$middleName = $data['middleName'];
$lastName = $data['lastName'];
$contactNo = $data['contactNo'];

// SQL Query to insert user data into the database
$sql = "INSERT INTO users (email, password, firstName, middleName, lastName, contactNo) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Check if the query preparation was successful
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare SQL query']);
    exit;
}

$stmt->bind_param("ssssss", $email, $password, $firstName, $middleName, $lastName, $contactNo);

// Execute the query and check if the user is registered successfully
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'User registered successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'User registration failed: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
