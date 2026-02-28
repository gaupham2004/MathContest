<?php
require_once 'pdo.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access denied. Only admins can upload.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $testType = $_POST['test_type'] ?? '';
    $testName = trim($_POST['test_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $grade = trim($_POST['grade'] ?? '');
    $timeLimit = intval($_POST['time_limit'] ?? 0);
    $questions = $_POST['questions'] ?? [];
    $numQuestions = count($questions);

    $allowedCategories = ['Primary', 'Junior', 'Senior', 'Open'];
    $allowedGrades = [
        'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5',
        'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9',
        'Grade 10', 'Grade 11', 'Grade 12'
    ];
    $categoryGradeMap = [
        'Primary' => ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6', 'Grade 7'],
        'Junior' => ['Grade 8', 'Grade 9'],
        'Senior' => ['Grade 10', 'Grade 11', 'Grade 12'],
        'Open' => $allowedGrades
    ];

    $errors = [];

    if (empty($testName)) {
        $errors[] = "Test name is required.";
    }
    if ($testType === 'official' && $timeLimit <= 0) {
        $errors[] = "Official test must have a valid time limit.";
    }
    if ($numQuestions <= 0) {
        $errors[] = "You must add at least one question.";
    }

    foreach ($questions as $index => $q) {
        if (
            empty(trim($q['text'])) ||
            empty(trim($q['a'])) ||
            empty(trim($q['b'])) ||
            empty(trim($q['c'])) ||
            empty(trim($q['d'])) ||
            empty(trim($q['correct']))
        ) {
            $errors[] = "Question " . ($index + 1) . " is incomplete.";
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'> " . htmlspecialchars($error) . "</p>";
        }
        exit;
    }

    try {
        if ($testType === 'official') {
            $stmt = $pdo->prepare("INSERT INTO official_tests (name, grade, category, num_questions, time_limit) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$testName, $grade, $category, $numQuestions, $timeLimit]);
            $testId = $pdo->lastInsertId();

            $stmtQ = $pdo->prepare("INSERT INTO official_questions (test_id, question_test, option_a, option_b, option_c, option_d, correct_option)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        } else {
            $stmt = $pdo->prepare("INSERT INTO training_tests (name, grade, category, num_questions) VALUES (?, ?, ?, ?)");
            $stmt->execute([$testName, $grade, $category, $numQuestions]);
            $testId = $pdo->lastInsertId();

            $stmtQ = $pdo->prepare("INSERT INTO training_questions (training_id, question_test, option_a, option_b, option_c, option_d, correct_option)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        }

        foreach ($questions as $q) {
            $stmtQ->execute([
                $testId,
                trim($q['text']),
                trim($q['a']),
                trim($q['b']),
                trim($q['c']),
                trim($q['d']),
                strtoupper(trim($q['correct']))
            ]);
        }

        $_SESSION['testM'] = "Test and questions uploaded successfully!";
    } catch (Exception $e) {
        echo "<p style='color:red;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

<html>
<head>
    <title>Admin Upload Test</title>
    <link rel="stylesheet" href="css/QUpload.css">
    <link rel="stylesheet" href="css/sidebar.css">
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

    <h2>Admin Test Upload</h2>
    <form method="post">
        <?php
        if(isset($_SESSION['testM'])){
            echo '<p style="color:green">'.htmlentities($_SESSION['testM'])."</p>\n";
            unset($_SESSION['testM']);
        }
        ?>
        <label>Test Type:</label>
        <select name="test_type" required>
            <option value="official">Official</option>
            <option value="training">Training</option>
        </select><br><br>

        <label>Test Name:</label>
        <input type="text" name="test_name" required><br><br>

        <label>Category:</label>
        <select name="category" id="category-select" onchange="updateGrades()" required>
            <option value="">--Select--</option>
            <option value="Primary">Primary</option>
            <option value="Junior">Junior</option>
            <option value="Senior">Senior</option>
            <option value="Open">Open</option>
        </select><br><br>

        <label>Grade:</label>
        <select name="grade" id="grade-select" required>
            <option value="">--Select--</option>
        </select><br><br>

        <label>Time Limit (official only):</label>
        <input type="number" name="time_limit" value="0"><br><br>

        <h3>Questions:</h3>
        <div id="question-container"></div>
        <button type="button" onclick="addQuestion()">Add Question</button><br><br>

        <input type="submit" value="Upload Test">
    </form>
</div>
<script>
let questionIndex = 0;

function addQuestion() {
    const container = document.getElementById("question-container");

    const block = document.createElement("div");
    block.className = "question-block";
    block.setAttribute("data-index", questionIndex);

    block.innerHTML = `
        <button type="button" class="remove-btn" onclick="removeQuestion(this)">Delete</button>
        <h4 class="question-number">Question ?</h4>
        <label>Question Text:</label><br>
        <input type="text" name="questions[${questionIndex}][text]" required><br>
        A: <input type="text" name="questions[${questionIndex}][a]" required><br>
        B: <input type="text" name="questions[${questionIndex}][b]" required><br>
        C: <input type="text" name="questions[${questionIndex}][c]" required><br>
        D: <input type="text" name="questions[${questionIndex}][d]" required><br>
        Correct Option(Example: A): <input type="text" name="questions[${questionIndex}][correct]" required><br>
    `;

    container.appendChild(block);
    questionIndex++;
    renumberQuestions();
}

function removeQuestion(button) {
    const block = button.closest(".question-block");
    block.remove();
    renumberQuestions();
}

function renumberQuestions() {
    const blocks = document.querySelectorAll(".question-block");
    blocks.forEach((block, index) => {
        const title = block.querySelector(".question-number");
        title.textContent = `Question ${index + 1}`;
    });
}

function updateGrades() {
    const gradeSelect = document.getElementById("grade-select");
    const category = document.getElementById("category-select").value;
    let grades = [];

    switch (category) {
        case "Primary":
            grades = ["Grade 1", "Grade 2", "Grade 3", "Grade 4", "Grade 5"];
            break;
        case "Junior":
            grades = [ "Grade 6", "Grade 7", "Grade 8", "Grade 9"];
            break;
        case "Senior":
            grades = ["Grade 10", "Grade 11", "Grade 12"];
            break;
        case "Open":
            grades = ["Grade 1", "Grade 2", "Grade 3", "Grade 4", "Grade 5", "Grade 6", "Grade 7", "Grade 8", "Grade 9", "Grade 10", "Grade 11", "Grade 12"];
            break;
        default:
            grades = [];
    }

    gradeSelect.innerHTML = '<option value="">--Select--</option>';
    grades.forEach(grade => {
        const option = document.createElement("option");
        option.value = grade;
        option.textContent = grade;
        gradeSelect.appendChild(option);
    });
}

addQuestion();
</script>

</body>
</html>
