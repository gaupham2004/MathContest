<?php
// config.sample.php
// RENAME THIS FILE TO config.php AND FILL IN YOUR DETAILS

$host = 'localhost';
$db_name = 'math_contest';
$username = 'YOUR_DB_USER';
$password = 'YOUR_DB_PASSWORD';

// If you have payment keys for your register page
$stripe_api_key = 'sk_test_XXXXX'; 

$conn = mysqli_connect($host, $username, $password, $db_name);
?>