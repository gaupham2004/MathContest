<?php
require_once 'pdo.php';

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in.");
}

$userId = $_SESSION['user_id'];
$testId = $_POST['test_id'] ?? null;

if (!$testId) {
    die("Test ID missing.");
}

$stmt = $pdo->prepare("SELECT id, correct_option FROM official_questions WHERE test_id = ?");
$stmt->execute([$testId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalQuestions = count($questions);
$score = 0;

foreach ($questions as $q) {
    $questionId = $q['id'];
    $correct = strtoupper($q['correct_option']);
    $userAnswer = strtoupper($_POST["q$questionId"] ?? ''); 

    if ($userAnswer === $correct) {
        $score++;
    }
}

$now = date('Y-m-d H:i:s');

$insert = $pdo->prepare("INSERT INTO official_results 
    (user_id, test_id, score, total_questions, date_taken)
    VALUES (?, ?, ?, ?, ?)");

$insert->execute([$userId, $testId, $score, $totalQuestions, $now]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Test Result</title>
  <link rel="stylesheet" href="css/afterTest.css" />
  <link rel="stylesheet" href="css/sideBar.css">
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
      <div class="result-box">
        <h1>Official Test Result</h1>
        <p><strong>Score:</strong> <?= $score ?> / <?= $totalQuestions ?></p>
        <p><strong>Date:</strong> <?= date("F j, Y, g:i a", strtotime($now)) ?></p>
        <p class="message">Your result has been saved successfully.</p>
        <a href="result.php" class="btn">Go to Results Page</a>
      </div>
    </div>
  </div>
</body>
</html>