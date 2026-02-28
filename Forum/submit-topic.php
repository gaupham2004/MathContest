<?php
require_once "pdo.php";
require_once "forum-notification.php";
session_start();

$title = trim($_POST['title'] ?? '');
$category = $_POST['category'] ?? 'General Discussion';
$description = trim($_POST['message'] ?? '');

if (!$title || !$description || !isset($_SESSION['user_id'])) {
    header("Location: forum.php?error=1");
    exit;
}

$stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
$username = $userRow['username'] ?? 'Anonymous';

$insert = $pdo->prepare("INSERT INTO topics (title, author, category, description) VALUES (?, ?, ?, ?)");
$insert->execute([$title, $username, $category, $description]);

$newTopicId = $pdo->lastInsertId();

$subject = "New Topic: $title by $username";
$body = "
  <h3>New Topic Created in Mathema Forum</h3>
  <p><strong>Title:</strong> $title</p>
  <p><strong>Author:</strong> $username</p>
  <p><strong>Category:</strong> $category</p>
  <p><a href='http://localhost/MathemaContest/forumTopic.php?id=$newTopicId'>Click to view topic</a></p>
";
sendForumNotification($pdo, $subject, $body, $_SESSION['user_id']);
header("Location: forum.php?");
exit;
