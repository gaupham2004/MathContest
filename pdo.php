<?php
$host = 'localhost';
$port = '3306';
$dbname = 'user_system';
$user = 'root';
$pass = 'root';

try {
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=$dbname", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage());
}
?>