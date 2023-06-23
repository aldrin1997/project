<?php
require_once 'config.php';

// Check if the method used is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $email = $data->email;
    $password = $data->password;
    
    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die(json_encode(["success" => false, "message" => "Email not found."]));
    }
    $user = $result->fetch_assoc();
    $stmt->close();
    
    // Check if the password is correct
    if (!password_verify($password, $user['password'])) {
        die(json_encode(["success" => false, "message" => "Password incorrect."]));
    }
    
    // Start a new session and set user ID in session data
    session_start();
    $_SESSION['user_id'] = $user['id'];
    
    echo json_encode(["success" => true, "message" => "Login successful."]);
}
?>
