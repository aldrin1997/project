<?php
require_once 'config.php';

// Check if the method used is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $data = json_decode(file_get_contents("php://input"));
    $content = $data->content;
    $user_id = $_SESSION['user_id'];
    $date_tweeted = date("Y-m-d H:i:s");
    
    // Insert the tweet data into the database
    $stmt = $conn->prepare("INSERT INTO tweets (content, date_tweeted, user_id) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $content, $date_tweeted, $user_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => true, "message" => "Tweet created."]);
} else {
    die(json_encode(["success" => false, "message" => "User not logged in."]));
}
?>
