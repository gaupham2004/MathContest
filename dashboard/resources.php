<?php
session_start();
require_once 'pdo.php';
$category = $_GET['category'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>PDF Resources - Mathema</title>
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/resources.css" />
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
        <li class="logout"><a href="../MyMathema.php"><img src="images/logout.png"> Logout</a></li>
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
    <div class="pdf-header">
      <h1>PDF Resources</h1>
      <p class="subtext">Study Materials</p>
    </div>

    <div class="filter-section">
      <label for="categoryFilter">Filter by category:</label>
      <select id="categoryFilter">
        <?php
          $categories = ['Primary', 'Junior', 'Senior', 'Open'];
          foreach ($categories as $cat) {
              $selected = ($cat === $category) ? 'selected' : '';
              echo "<option value=\"$cat\" $selected>$cat</option>";
          }
        ?>
      </select>
    </div>

    <div class="resource-list">
      <?php
      $stmt = $pdo->prepare("SELECT * FROM resources WHERE category = ? AND status = 'approved' ORDER BY uploaded_at DESC");
      $stmt->execute([$category]);
      $resources = $stmt->fetchAll();

      if (empty($resources)) {
          echo "<p>No resources uploaded yet for your category.</p>";
      } else {
          foreach ($resources as $res) {
              echo "<div class='resource-card'>";
              echo "<h4>" . htmlspecialchars($res['title']) . "</h4>";
              echo "<p>" . htmlspecialchars($res['description']) . "</p>";
              echo "<p>Uploaded: " . date('F j, Y', strtotime($res['uploaded_at'])) . "</p>"."<br>";
              echo "<a class='download-btn' href='uploads/" . htmlspecialchars($res['filename']) . "' download>Download PDF</a>";
              echo "</div>";
          }
      }
      ?>
    </div>

    <div class="upload-section">
      <h3>Upload New PDF Resource</h3>
      <form action="upload-resource.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="description">Short Description:</label>
        <textarea name="description" rows="2" required></textarea>

        <label for="category">Category:</label>
        <select name="category" required>
          <option value="Primary">Primary</option>
          <option value="Junior">Junior</option>
          <option value="Senior">Senior</option>
          <option value="Open">Open</option>
        </select>

        <label for="pdf">Select PDF File:</label>
        <input type="file" name="pdf" accept="application/pdf" required>

        <button type="submit" class="action-button">Submit for Review</button>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('categoryFilter').addEventListener('change', function () {
  window.location.href = 'resources.php?category=' + encodeURIComponent(this.value);
});
</script>

</body>
</html>
