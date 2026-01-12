<?php
session_start();
require("../includes/db_connect.php");

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userID = (int) $_SESSION['userID'];
    $post_id = (int) $_POST['post_id'];
    $comment = $_POST['comment_input'] ?? '';

    if (!isset($userID)) {
        header("Location: login.php");
        exit;
    }

    if (!isset($post_id)) {
        header("Location: login.php");
        exit;
    }

    if (!isset($comment)) {
        header("Location: login.php");
        exit;
    }

    $comment = trim($comment);

    if ($comment === '') {
        echo 'empty comment';
    }

    try {
        $stmt = $conn->prepare("INSERT INTO comments (postID, userID, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $userID, $comment);
        $stmt->execute();

        $conn->close();
        $stmt->close();
    } catch (Exception) {
        header("Location: dashboard.php");
        exit;
    }
    header('Location: dashboard.php');
    exit;
}

// Support fetching comments for a post via GET returning JSON
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id'])) {
    $post_id = (int) $_GET['post_id'];

    try {
        $stmt = $conn->prepare("SELECT commentID, postID, userID, comment, commentDate FROM comments WHERE postID = ? ORDER BY commentDate ASC");
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $res = $stmt->get_result();

        $comments = []; // Initialize an array to hold comments

        while ($row = $res->fetch_assoc()) { // Fetch each comment as an associative array
            $comments[] = $row;
        }

        header('Content-Type: application/json'); // Set header for JSON response
        echo json_encode($comments); // Encode the comments array as JSON and output it
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json', true, 500);
        echo json_encode(['error' => 'Could not fetch comments']);
        exit;
    }
}
