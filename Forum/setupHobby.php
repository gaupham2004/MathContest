<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$category = $_SESSION['category'] ?? 'Unknown';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $education = $_POST['education_level'] ?? '';
    $interests = $_POST['interests'] ?? '';
    $career_goal = $_POST['career_goal'] ?? '';
    $category = $_POST['category'] ?? 'Unknown';

    $check = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
    $check->execute([$user_id]);

    if ($check->fetch()) {
        $stmt = $pdo->prepare("UPDATE user_profiles SET education = ?, interests = ?, goals = ?, category = ? WHERE user_id = ?");
        $stmt->execute([$education, $interests, $career_goal, $category, $user_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO user_profiles (user_id, education, interests, goals, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $education, $interests, $career_goal, $category]);
    }

    header("Location: forum.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Profile Setup</title>
  <link rel="stylesheet" href="css/setupCSS.css" />
</head>
<body>

<div class="profile-setup-container">
  <h2>Complete Your Profile</h2>
  <form method="POST">

    <label for="category">Your Contest Category</label>
    <input type="text" id="category" name="category" value="<?= htmlspecialchars($category) ?>" readonly />

    <label for="interests">Your Interests</label>
    <textarea id="interests" name="interests" placeholder="e.g., Math, Coding, Robotics" required></textarea>

    <label for="career_goal">Future Career Goal</label>
    <select id="career_goal" name="career_goal" required>
      <option value="">-- Select One --</option>
      <option value="Software Engineer">Software Engineer</option>
      <option value="Data Scientist">Data Scientist</option>
      <option value="Math Teacher">Math Teacher</option>
      <option value="AI Researcher">AI Researcher</option>
      <option value="Engineer">Engineer</option>
      <option value="Entrepreneur">Entrepreneur</option>
      <option value="Other">Other</option>
    </select>

    <label for="education_level">Highest Education Level</label>
    <select id="education_level" name="education_level" required>
      <option value="">-- Select One --</option>
      <option value="Elementary School">Elementary School</option>
      <option value="Middle School">Middle School</option>
      <option value="High School">High School</option>
      <option value="College / University">College / University</option>
      <option value="Graduate School">Graduate School</option>
      <option value="Postgraduate / Doctorate">Postgraduate / Doctorate</option>
      <option value="Other">Other</option>
    </select>

    <button type="submit">Save Profile</button>
  </form>
</div>
<script>
  const category = document.getElementById("category").value.trim().toLowerCase();
  const educationSelect = document.getElementById("education_level");

  const options = {
    primary: [
      "Elementary School",
      "Middle School",
      "Other"
    ],
    junior: [
      "Middle School",
      "High School",
      "Other"
    ],
    senior: [
      "High School",
      "College / University",
      "Other"
    ],
    open: [
      "High School",
      "College / University",
      "Graduate School",
      "Postgraduate / Doctorate",
      "Other"
    ]
  };

  const allOptions = [
    "Elementary School",
    "Middle School",
    "High School",
    "College / University",
    "Graduate School",
    "Postgraduate / Doctorate",
    "Other"
  ];

  function populateOptions(allowed) {
    educationSelect.innerHTML = '<option value="">-- Select One --</option>';
    allowed.forEach(level => {
      const opt = document.createElement("option");
      opt.value = level;
      opt.textContent = level;
      educationSelect.appendChild(opt);
    });
  }
  populateOptions(options[category] || allOptions);
</script>

</body>
</html>
