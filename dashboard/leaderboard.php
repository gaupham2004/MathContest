<?php
require_once 'pdo.php';
session_start();
$category = $_SESSION['category'] ?? ''; 

$stmt = $pdo->prepare("
    SELECT DISTINCT t.id, t.name
    FROM official_tests t
    JOIN official_results r ON r.test_id = t.id
    JOIN users u ON u.id = r.user_id
    WHERE u.category = :cat
    ORDER BY t.name
");
$stmt->execute([':cat' => $category]);
$tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Leaderboard - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/leaderboard.css">
</head>
<body>
  <div class="layout">
    <div class="sidebar">
      <div class="logo">
        <img src="images/logo.png" alt="Mathema Logo">
        <h2>Mathema</h2>
      </div>
      <ul>
        <li class="sidebarItems"><a href="dashboard.php"><img src="images/home.png" alt=""> Dashboard</a></li>
        <li class="sidebarItems"><a href="profile-page.php"><img src="images/profile.png" alt=""> Profile</a></li>
        <li class="sidebarItems"><a href="resources.php"><img src="images/resources.png" alt=""> Study Materials</a></li>
        <li class="sidebarItems"><a href="result.php"><img src="images/result.png" alt=""> Result</a></li>
        <li class="sidebarItems"><a href="leaderboard.php"><img src="images/rank.png" alt=""> Leaderboard</a></li>
        <li class="logout"><a href="../MyMathema.php"><img src="images/logout.png" alt=""> Logout</a></li>
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
      <h1>Leaderboard - <?= htmlspecialchars($category) ?> Category</h1>

      <?php foreach ($tests as $test): ?>
        <div class="test-block">
          <h2><?= htmlspecialchars($test['username']) ?></h2>
          <ol>
            <?php
$rankStmt = $pdo->prepare("
              SELECT u.name, ROUND((r.score / r.total_questions) * 100, 2) AS percentage
              FROM official_results r
              JOIN users u ON u.id = r.user_id
              WHERE r.test_id = :test_id AND u.category = :cat
              ORDER BY percentage DESC, r.date_taken ASC
              LIMIT 10
            ");
            $rankStmt->execute([
              ':test_id' => $test['id'],
              ':cat' => $category
            ]);
            $ranked = $rankStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($ranked)) {
              echo "<li>No results yet for this test.</li>";
            } else {
              foreach ($ranked as $row) {
                echo "<li><strong>" . htmlspecialchars($row['username']) . "</strong> — " . $row['percentage'] . "%</li>";
              }
            }
            ?>
          </ol>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
