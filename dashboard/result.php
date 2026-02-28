<!DOCTYPE html>
<?php
session_start();
require_once 'pdo.php';

$userID = $_SESSION['user_id'] ?? '';
$stmt = $pdo->prepare("
  SELECT r.*, t.name AS test_name, ROUND((r.score / r.total_questions) * 100, 1) AS percentage
  FROM official_results r
  JOIN official_tests t ON r.test_id = t.id
  WHERE r.user_id = ?
  ORDER BY r.date_taken DESC
");
$stmt->execute([$userID]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Result - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/result.css">
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
      <h1>Test Results</h1>
      <div class="box">
        <table class="result-table">
          <thead>
            <tr>
              <th>Test Name</th>
              <th>Score</th>
              <th>Percentage</th>
              <th>Date Taken</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($results): ?>
              <?php foreach ($results as $res): ?>
                <tr>
                  <td><?= htmlspecialchars($res['test_name']) ?></td>
                  <td><?= $res['score'] ?> / <?= $res['total_questions'] ?></td>
                  <td><?= $res['percentage'] ?>%</td>
                  <td>  <?php
                    $date = new DateTime($res['date_taken']);
                    echo $date->format('F j, Y');
                  ?></td>
                  <td><button onclick="alert('Coming soon!')">Download</button></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="4">No test results found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
