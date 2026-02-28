<!DOCTYPE html>
<?php
require_once 'pdo.php';
session_start();

$userID = $_SESSION['user_id']??'';
$stmt = $pdo->prepare("SELECT username, email, category, role FROM users WHERE id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$_SESSION['role'] = $user['role']
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="layout">
      <div class="sidebar">
      <div class="logo">
        <img src="images/logo.png" alt="Mathema Logo">
        <h2>Mathema</h2>
      </div>
      <ul>
        <li class="sidebarItems"><a href="dashboard.php"><img src="images/home.png"> Dashboard</a></li>
        <li class="sidebarItems"><a href="profile-page.php"><img src="images/profile.png"> Profile</a></li>
        <li class="sidebarItems"><a href="resources.php"><img src="images/resources.png"> Study Materials</a></li>
        <li class="sidebarItems"><a href="result.php"><img src="images/result.png"> Result</a></li>
        <li class="sidebarItems"><a href="leaderboard.php"><img src="images/rank.png" alt=""> Leaderboard</a></li>
        <li class = "logout"><a href="../MyMathema.php"><img src="images/logout.png"> Logout</a></li>
      </ul>
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <h4>Admin:</h4>
        <ul>
          <li class="sidebarItems"><a href="admin-upload.php"><img src="images/test.png"> Upload Test</a></li>
          <li class="sidebarItems"><a href="review-pending.php"><img src="images/approve.png"> Approve PDFs</a></li>        
        </ul>
      <?php endif; ?>
    </div>

    <div class="main-content">
      <div class="welcome-box">
        <h1>Welcome, <?php echo $user['username'] ?>!</h1>
        <p class="subtext">Ready to explore your dashboard?</p>
      </div>

      <div class="account-info">
        <div class="info-item">
          <label>Email</label>
          <span><?php echo $user['email']?></span>
        </div>
        <div class="info-item">
          <label>Category</label>
          <span><?php echo $user['category']?></span>
        </div>
      </div>

      <div class="box">
        <h3>Official Test</h3>
        <p>Practice questions to prepare for the official test.</p>
        <a href="official-test.php" class="action-button">Start Official Test</a>
      </div>

      <div class="box">
        <h3>Training Test</h3>
        <p>You can only attempt this once. Be prepared.</p>
        <a href="training-page.php" class="action-button">Start Training</a>
      </div>

      <div class="box">
        <h3>Upload PDF</h3>
        <p>Send us your material. Our team will review and organize them for others.</p>
        <button class="action-button">Upload PDF</button>
      </div>
    </div>
  </div>
    </div>
</body>
</html>
