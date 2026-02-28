<?php
session_start();
require_once 'pdo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("UPDATE resources SET status = 'approved' WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("SELECT filename FROM resources WHERE id = ?");
        $stmt->execute([$id]);
        $file = $stmt->fetchColumn();

        if ($file && file_exists("uploads/$file")) {
            unlink("uploads/$file");
        }

        $stmt = $pdo->prepare("DELETE FROM resources WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: review-pending.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM resources WHERE status = 'pending' ORDER BY uploaded_at DESC");
$pendingResources = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Review PDF Submissions - Mathema</title>
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
      <h4>Admin:</h4>
      <ul>
        <li class="sidebarItems"><a href="admin-upload.php"><img src="images/test.png"> Upload Test</a></li>
        <li class="sidebarItems"><a href="review-pending.php"><img src="images/approve.png"> Approve PDFs</a></li>
      </ul>
    </div>

    <div class="main-content">
        <div class="pdf-header">
            <h1>Pending PDF Approvals</h1>
            <p class="subtext">Review and approve uploaded resources</p>
        </div>
        <div class="resource-list">
            <?php if (empty($pendingResources)) : ?>
                <div class="no-resource">No pending resources to review.</div>
            <?php else : ?>
                <?php foreach ($pendingResources as $res) : ?>
                    <div class="resource-card">
                        <div class="resource-card-content">
                            <h4><?= htmlspecialchars($res['title']) ?></h4>
                            <p><?= htmlspecialchars($res['description']) ?></p>
                            <p>Category: <?= htmlspecialchars($res['category']) ?></p>
                            <p>Uploaded: <?= date('F j, Y', strtotime($res['uploaded_at'])) ?></p>
                        </div>
                        <div class="action-buttons">
                            <a href="uploads/<?= htmlspecialchars($res['filename']) ?>" target="_blank" class="button view">View PDF</a>
                            <form action="review-pending.php" method="post">
                                <input type="hidden" name="id" value="<?= $res['id'] ?>">
                                <button type="submit" name="action" value="approve" class="button approve">Approve</button>
                                <button type="submit" name="action" value="delete" class="button delete">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
