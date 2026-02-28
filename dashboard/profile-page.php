<!DOCTYPE html>
<?php
session_start();
require_once 'pdo.php';

$userID = $_SESSION['user_id']??'';
$stmt = $pdo->prepare("SELECT username, email, phone FROM users WHERE id = ?");
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Profile - Mathema</title>
  <link rel="stylesheet" href="css/profile.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
</head>
<body>
  <div class="container">
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
      <div class="profile-header">
        <h1>My Profile</h1>
      </div>

      <form action="update_profile.php" method="POST">
        <div class="profile-container">
          <div class="profile-box">
            <h3>Basic Information</h3>

            <div class="form-row">
              <label for="name">Name</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($user['username'])?>" required />
            </div>

            <div class="form-row">
              <label for="email">Email</label>
              <input type="email" name="email" value="<?php echo htmlspecialchars($user['email'])?>" disabled />
            </div>

            <div class="form-row">
              <label for="phone">Phone</label>
              <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" />
            </div>
          </div>

          <div class="profile-box">
            <h3>System Details</h3>

            <div class="form-row">
              <label for="category">Category</label>
              <input type="text" name="category" value="Primary" disabled />
            </div>

            <div class="form-row">
              <label for="type">Type</label>
              <input type="text" name="type" value="Individual" disabled />
            </div>

            <div class="form-row">
              <label for="type">Language</label>
              <select name="language" id="language">
                <option value="english" selected>English</option>
              </select>
            </div><br><br>

            <button type="submit" class="action-button">Update Profile</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
