<?php
require_once 'pdo.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? 'Junior';
    $file = $_FILES['pdf'] ?? null;

    if ($file && $file['error'] === 0 && $file['type'] === 'application/pdf') {
        $filename = uniqid() . "-" . basename($file['name']);
        $target = "uploads/" . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $stmt = $pdo->prepare("INSERT INTO resources (title, description, filename, category) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $description, $filename, $category]);
            header("Location: resources.php?upload=success");
            exit;
        }
    }

    header("Location: resources.php?upload=error");
}
