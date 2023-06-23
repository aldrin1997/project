<?php
require_once 'config.php';

// Check if the method used is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $_SESSION['user_id'];
    $old_password = $data->old_password;
    $new_password = password_hash($data->new_password, PASSWORD_DEFAULT);
    
    // Check if the old password is correct
    $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    if (!password_verify($old_password, $user['password'])) {
        die(json_encode(["success" => false, "message" => "Old password incorrect."]));
    }
    
    // Update the password in the database
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $stmt->bind_param("si", $new_password, $user_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => true, "message" => "Password changed."]);
}
?>
