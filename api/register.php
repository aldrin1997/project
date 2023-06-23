<?php
require_once 'config.php';

// Check if the method used is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $firstname = $data->firstname;
    $lastname = $data->lastname;
    $email = $data->email;
    $birthdate = $data->birthdate;
    $password = password_hash($data->password, PASSWORD_DEFAULT);
    
    // Check if the email is already taken
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die(json_encode(["success" => false, "message" => "Email already taken."]));
    }
    $stmt->close();
    
    // Insert the user data into the database
    $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, email, birthdate, password) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $firstname, $lastname, $email, $birthdate, $password);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => true, "message" => "Registration successful."]);
}
?>