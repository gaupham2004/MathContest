<!DOCTYPE html>
<?php
  require_once 'pdo.php';
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Training - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/test-hall.css" />
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
      <h1>Training Tests</h1>
      <?php  
        $tq = $pdo->prepare("SELECT * FROM training_tests");
        $tq->execute();
        $test = $tq->fetchAll(PDO::FETCH_ASSOC);
        foreach($test as $index => $test){
          $tid = $test['id']; 
          $tname = htmlspecialchars($test['name']);
          $category = htmlspecialchars($test['category']);
          $num_q = htmlspecialchars($test['num_questions']);

          echo "
            <div class='box'>
              <p><strong>$tname</strong></p>
              <p>$num_q questions - Unlimited attempts</p>
              <p>Category: $category</p>
              <a href='training-test.php?id=$tid'>
                <button class='action-button'>Start Training</button>
              </a>
            </div>";
        }
      ?>
    </div>
  </div>
</body>
</html>
