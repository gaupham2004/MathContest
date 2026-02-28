<!DOCTYPE html>
<?php
require_once 'pdo.php';

$testId = $_GET['id'] ?? null;
if (!$testId) {
    die("No training test selected.");
}

$tq = $pdo->prepare("SELECT * FROM training_questions WHERE training_id = ?");
$tq->execute([$testId]);
$questions = $tq->fetchAll(PDO::FETCH_ASSOC);
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Training Test - Mathema</title>
  <link rel="stylesheet" href="css/test.css" />
</head>
<body>
  <div class="test-container">
    <div class="test-header">
      <h2>Training Test</h2>
      <div class="timer">Practice Mode</div>
    </div>

    <form method="post" action="training-result.php">
      <?php
        foreach ($questions as $index => $question) {
          $qid = $question['id']; 
          $qtext = htmlspecialchars($question['question_test']);
          $a = htmlspecialchars($question['option_a']);
          $b = htmlspecialchars($question['option_b']);
          $c = htmlspecialchars($question['option_c']);
          $d = htmlspecialchars($question['option_d']);

          echo "
          <div class='question'>
            <h4>" . ($index + 1) . ". $qtext</h4>
            <label><input type='radio' name='q$qid' value='A'> $a</label>
            <label><input type='radio' name='q$qid' value='B'> $b</label>
            <label><input type='radio' name='q$qid' value='C'> $c</label>
            <label><input type='radio' name='q$qid' value='D'> $d</label>
          </div>";
        }
      ?>
      <input type="hidden" name="test_id" value="<?= $testId ?>">
      <button type="submit" class="submit-btn">Check Answer</button>
    </form>
  </div>
</body>
</html>
