<?php
require_once 'config.php';

// Check if the method used is GET and user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SESSION['user_id'])) {
    // Fetch all tweets from the database
    $stmt = $conn->prepare("SELECT * FROM tweets JOIN users ON tweets.user_id=users.id ORDER BY date_tweeted DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $tweets = [];
    while ($row = $result->fetch_assoc()) {
        $tweets[] = [
            "id" => $row["id"],
            "content" => $row["content"],
            "date_tweeted" => $row["date_tweeted"],
            "user_id" => $row["user_id"],
            "firstname" => $row["firstname"],
            "lastname" => $row["lastname"],
            "email" => $row["email"],
            "birthdate" => $row["birthdate"]
        ];
    }
    $stmt->close();
    
    // Return the tweets as a JSON array
    echo json_encode(["success" => true, "tweets" => $tweets]);
} else {
    die(json_encode(["success" => false, "message" => "User not logged in."]));
}
?>
