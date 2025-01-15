<?php
session_start();

$response = ['status' => 'pending']; // Default response status

// Check if the user session exists
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Connect to the database
    $conn = new mysqli('localhost', 'root', '', 'isp');
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Fetch user status from the database
    $sql = "SELECT status FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->bind_result($userStatus);
        $stmt->fetch();
        $stmt->close();

        // If the status is 1, set response status to active
        if ($userStatus == 1) {
            $response['status'] = 'active';
        }
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
    }
    $conn->close();
} else {
    error_log("User session not set");
}

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
