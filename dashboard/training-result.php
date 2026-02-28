<?php
session_start();
require_once 'pdo.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request.");
}

$trainingId = $_POST['test_id'] ?? null;
if (!$trainingId) {
    die("Training ID missing.");
}

$stmt = $pdo->prepare("SELECT id, correct_option FROM training_questions WHERE training_id = ?");
$stmt->execute([$trainingId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$score = 0;
$total = count($questions);

foreach ($questions as $index => $question) {
    $userAnswer = $_POST['q' . ($index + 1)] ?? '';
    if (strtoupper($userAnswer) === strtoupper($question['correct_option'])) {
        $score++;
    }
}

$percentage = $total > 0 ? round(($score / $total) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Training Result</title>
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
                <h2>Training Test Result</h2>
                <p><strong>Correct Answers:</strong> <?= $score ?> / <?= $total ?></p>
                <p><strong>Percentage:</strong> <?= $percentage ?>%</p>
                <a class="btn" href="training-test.php?training_id=<?= htmlspecialchars($trainingId) ?>" class="back-btn">Try Again</a>
                <a class="btn" href="dashboard.php" class="back-btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
