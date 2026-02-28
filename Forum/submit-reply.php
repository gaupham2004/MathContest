<?php
require_once "pdo.php";
session_start();

$topic_id = $_POST['topic_id'] ?? 0;
$message = $_POST['message'] ?? '';

$users = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$users->execute([$_SESSION['user_id']]);
$userRow = $users->fetch(PDO::FETCH_ASSOC);
$username = $userRow['username'] ?? 'Anonymous';

if ($topic_id && $message) {
    $stmt = $pdo->prepare("INSERT INTO replies (topic_id, author, message) VALUES (?, ?, ?)");
    $stmt->execute([$topic_id, $username, $message]);
}

header("Location: forumTopic.php?id=" . $topic_id);
exit;
