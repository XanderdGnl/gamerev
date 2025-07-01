<?php
require_once 'connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $user_id = (int) $_SESSION['user_id'];
    $post_id = (int) $_POST['post_id'];

    $stmt = $pdo->prepare("SELECT 1 FROM post_likes WHERE user_id = ? AND post_id = ?");
    $stmt->execute([$user_id, $post_id]);

    if ($stmt->rowCount() === 0) {
        $insert = $pdo->prepare("INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)");
        $insert->execute([$user_id, $post_id]);

        $update = $pdo->prepare("UPDATE posts SET likes = likes + 1 WHERE id = ?");
        $update->execute([$post_id]);
    }
}

header("Location: index.php");
exit;
