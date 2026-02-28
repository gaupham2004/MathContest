<!DOCTYPE html>
<?php
session_start();
require_once 'pdo.php';

$testID = $_GET['test_id'] ?? null;

if (!$testID) {
    die("Test ID is required.");
}

$testStmt = $pdo->prepare("SELECT name, time_limit FROM official_tests WHERE id = ?");
$testStmt->execute([$testID]);
$test = $testStmt->fetch(PDO::FETCH_ASSOC);

if (!$test) {
    die("Invalid test.");
}

$testName = htmlspecialchars($test['name']);
$timeLimit = (int)$test['time_limit'];

$qStmt = $pdo->prepare("SELECT * FROM official_questions WHERE test_id = ?");
$qStmt->execute([$testID]);
$questions = $qStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= $testName ?> - Official Test</title>
  <link rel="stylesheet" href="css/test.css" />
</head>
<body>
  <div class="test-container">
    <div class="test-header">
      <h2><?= $testName ?> - Official Test</h2>
      <div class="timer" id="timer"><?= $timeLimit ?>:00</div>
    </div>

    <form id="testForm" method="post" action="submit-score.php">
      <input type="hidden" name="test_id" value="<?= $testID ?>">

      <?php foreach ($questions as $index => $q): 
        $qNum = $index + 1;
        $qName = "q" . $qNum;
      ?>
        <div class="question">
          <h4><?= $qNum ?>. <?= htmlspecialchars($q['question_test']) ?></h4>
          <label><input type="radio" name="<?= $qName ?>" value="A"> <?= htmlspecialchars($q['option_a']) ?></label>
          <label><input type="radio" name="<?= $qName ?>" value="B"> <?= htmlspecialchars($q['option_b']) ?></label>
          <label><input type="radio" name="<?= $qName ?>" value="C"> <?= htmlspecialchars($q['option_c']) ?></label>
          <label><input type="radio" name="<?= $qName ?>" value="D"> <?= htmlspecialchars($q['option_d']) ?></label>
        </div>
      <?php endforeach; ?>

      <button type="submit" class="submit-btn">Submit Test</button>
    </form>
  </div>

  <script>
    let time = <?= $timeLimit ?> * 60; 
    const timerDisplay = document.getElementById("timer");

    function updateTimer() {
      const minutes = Math.floor(time / 60);
      const seconds = time % 60;
      timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds
        .toString()
        .padStart(2, '0')}`;

      if (time <= 0) {
        document.getElementById("testForm").submit(); 
      } else {
        time--;
        setTimeout(updateTimer, 1000);
      }
    }

    updateTimer();
  </script>
</body>
</html>
