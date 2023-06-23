<?php
require_once 'config.php';

// Check if the method used is POST and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $data = json_decode(file_get_contents("php://input"));
    $tweet_id = $data->tweet_id;
    $user_id = $_SESSION['user_id'];
    
    // Check if the user made the tweet
    $stmt = $conn->prepare("SELECT * FROM tweets WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $tweet_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        die(json_encode(["success" => false, "message" => "User did not create the tweet or tweet does not exist."]));
    }
    
    // Delete the tweet from the database
    $stmt = $conn->prepare("DELETE FROM tweets WHERE id=?");
    $stmt->bind_param("i", $tweet_id);
    $stmt->execute();
    $stmt->close();
    
    echo json_encode(["success" => true, "message" => "Tweet deleted."]);
} else {
    die(json_encode(["success" => false, "message" => "User not logged in."]));
}
?>
