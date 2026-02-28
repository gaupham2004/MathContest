<!DOCTYPE html>
<?php
session_start();
require_once 'pdo.php';

$category = $_SESSION['category'] ?? '';
$selectedGrade = $_GET['grade'] ?? 'All';
$availableGrades = [
  "Primary" => ["Grade 1", "Grade 2", "Grade 3", "Grade 4", "Grade 5"],
  "Junior" => ["Grade 6", "Grade 7", "Grade 8"],
  "Senior" => ["Grade 9", "Grade 10", "Grade 11", "Grade 12"],
  "University" => ["Bachelor's", "Master's", "PhD"]
];

$tests = [];
if (!empty($category)) {
  if ($selectedGrade && $selectedGrade !== 'All') {
    $stmt = $pdo->prepare("SELECT * FROM official_tests WHERE category = :category AND grade = :grade");
    $stmt->execute([':category' => $category, ':grade' => $selectedGrade]);
  } else {
    $stmt = $pdo->prepare("SELECT * FROM official_tests WHERE category = :category");
    $stmt->execute([':category' => $category]);
  }
  $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Official Test - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css">
  <link rel="stylesheet" href="css/test-hall.css">
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
      <h1>Official Test</h1>

      <form method="get" action="official-test.php" style="margin-bottom: 30px;">
        <label for="grade">Select Grade:</label>
        <select name="grade" onchange="this.form.submit()" style="padding: 8px; border-radius: 6px; margin-left: 10px;">
          <option value="All" <?= ($selectedGrade === 'All' || $selectedGrade === '') ? 'selected' : '' ?>>All</option>
          <?php foreach ($availableGrades[$category] ?? [] as $g): ?>
            <option value="<?= $g ?>" <?= ($selectedGrade === $g) ? 'selected' : '' ?>><?= $g ?></option>
          <?php endforeach; ?>
        </select>
      </form>

      <?php foreach ($tests as $test): ?>
        <div class="box">
          <p><strong>Test Name:</strong> <?= htmlspecialchars($test['name']) ?></p>
          <p><strong>Number of Questions:</strong> <?= htmlspecialchars($test['num_questions']) ?></p>
          <p><strong>Category:</strong> <?= htmlspecialchars($test['category']) ?></p>
          <p><em>Note: Once submitted, this test cannot be retaken.</em></p>
          <button class="action-button" onclick="openModal(<?= $test['id'] ?>)">Start Test</button>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="modal-overlay" id="confirmationModal">
    <div class="modal">
      <h3 id="modal-title">You're about to begin</h3>
      <p><strong>Number of Questions:</strong> <span id="modal-questions">--</span></p>
      <p><strong>Time:</strong> 60 minutes</p>
      <ul>
        <li>This test cannot be retaken.</li>
        <li>Test will auto-submit when time is up.</li>
        <li>You will see your score immediately after submission.</li>
      </ul>
      <div class="modal-buttons">
        <button class="cancel" onclick="closeModal()">Cancel</button>
        <button class="start" id="startButton">Start Test</button>
      </div>
    </div>
  </div>

  <script>
    const testList = <?= json_encode($tests) ?>;

    function openModal(testId) {
      const test = testList.find(t => t.id == testId);
      if (!test) return;

      document.getElementById("modal-title").innerText = `You're about to begin "${test.name}"`;
      document.getElementById("modal-questions").innerText = test.num_questions;
      document.getElementById("startButton").onclick = () => {
        window.location.href = `start-test.php?test_id=${test.id}`;
      };
      document.getElementById("confirmationModal").style.display = "flex";
    }

    function closeModal() {
      document.getElementById("confirmationModal").style.display = "none";
    }
  </script>
</body>
</html>
