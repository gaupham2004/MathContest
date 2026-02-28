<?php
session_start();
require_once "pdo.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $category = strtolower($_POST['category'] ?? '');

    if ($category === "team") {
        $stmt = $pdo->prepare("SELECT * FROM teams WHERE email = :email");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND category = :category");
        $stmt->bindParam(':category', $category);
    }

    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($userData && password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $email;
        $_SESSION['category'] = $category;
        header("Location: index.php");
        exit;
    } else {
        $message = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="css/logStyle.css">
</head>
<body>

    <h2>Login</h2>
    <form method="post" action="login.php">
    <?php if (!empty($message)) echo "<p style='color:red; '>$message</p>"; ?>
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <select name="category" required>
        <option value="" disabled selected>Select category</option>
        <option value="primary">Primary</option>
        <option value="junior">Junior</option>
        <option value="senior">Senior</option>
        <option value="open">Open</option>
        <option value="team">Team of Four</option>
    </select><br>
    <p>If you do not have an account, <a href="register.php">please register to join the contest.</a></p>
    <button type="submit" class="btn">Login</button>
</form>

</body>
</html>
