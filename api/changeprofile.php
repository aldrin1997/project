<?php
require_once 'config.php';

// Check if the method used is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    $user_id = $_SESSION['user_id'];
    $firstname = $data->firstname;
    $lastname = $data->lastname;
    $email = $data->email;
    $birthdate = $data->birthdate;
    
    // Update the user data in the database
    $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, email=?, birthdate=? WHERE id=?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $birthdate, $user_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => true, "message" => "Profile updated."]);
}
?>
