<?php
session_start();
require_once "pdo.php";

$topic_id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM topics WHERE id = ?");
$stmt->execute([$topic_id]);
$topic = $stmt->fetch(PDO::FETCH_ASSOC);

$replyStmt = $pdo->prepare("SELECT * FROM replies WHERE topic_id = ? ORDER BY replied_at ASC");
$replyStmt->execute([$topic_id]);

$profileStmt = $pdo->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$profileStmt->execute([$_SESSION['user_id']]);
$profile = $profileStmt->fetch(PDO::FETCH_ASSOC); 

$users = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$users->execute([$_SESSION['user_id']]);
$userName = $users->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forum Topic - Mathema Contest</title>
  <link rel="stylesheet" href="css/forumCSS.css">
  <link rel="stylesheet" href="css/headerStyle.css">
</head>
<body>
  <header>
    <div class="leftSide">
      <div class="logo">
        <a href="index.html">
          <img class="logoImg" src="images/logo.png" alt="Logo"> MC
        </a>
      </div>
    </div>
    <div class="rightSide">
      <nav>
        <ul class="navList">
          <li class="navigateEle"><a href="welcome.html">Welcome</a></li>             
          <li class="navigateEle"><a href="contact.html">Contact</a></li>
          <li class="dropdown">
            <img src="images/more.png" alt="More Menu">
            <ul class="dropdown-menu">
              <li><a href="category.html">Category</a></li>
              <li><a href="forum.php">Forum</a></li>
              <li><a href="info.html">Detail</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="forumBoard">
    <h1>Forum</h1>
  </div>

  <main>
    <div class="forumLayout">
      <div class="leftContent">

        <div class="originalPostDiv">
          <h1>Topic:</h1>
          <section class="originalPost">
            <h2><?= htmlspecialchars($topic['title']) ?></h2>
            <div class="postMeta">Posted by <strong><?= htmlspecialchars($topic['author']) ?></strong> • <?= htmlspecialchars($topic['created_at']) ?></div>
            <p><?= nl2br(htmlspecialchars($topic['description'] ?? 'No description available.')) ?></p>
          </section>
        </div>

        <section class="replies">
          <h3>Replies</h3>
          <?php while ($reply = $replyStmt->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="replyBox">
              <div class="replyMeta">by <strong><?= htmlspecialchars($reply['author']) ?></strong> • <?= htmlspecialchars($reply['replied_at']) ?></div>
              <p><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
            </div>
          <?php endwhile; ?>
        </section>

        <section class="replyForm">
          <h3>Post a Reply</h3>
          <form method="POST" action="submit-reply.php">
            <input type="hidden" name="topic_id" value="<?= $topic_id ?>">
            <textarea name="message" required placeholder="Write your reply..."></textarea>
            <button type="submit">Post Reply</button>
          </form>
        </section>
      </div>

      <aside class="forumSidebar">
        <article class="sidebarBox">
          <h3>Your Profile</h3>
          <ul>
            <?php if ($profile): ?>
              <li><strong>Category:</strong> <?= htmlspecialchars($profile['category']) ?></li>
              <li><strong>Education:</strong> <?= htmlspecialchars($profile['education']) ?></li>
              <li><strong>Interests:</strong> <?= htmlspecialchars($profile['interests']) ?></li>
              <li><strong>Goals:</strong> <?= htmlspecialchars($profile['goals']) ?></li>
            <?php else: ?>
              <li>Profile not completed.</li>
            <?php endif; ?>
          </ul>
        </article>

        <article class="sidebarBox">
          <h3>Forum Sections</h3>
          <ul>
            <li>Math Tips & Tricks</li>
            <li>Contest Preparation</li>
            <li>Questions & Answers</li>
            <li>General Discussion</li>
            <li>Technical Issues</li>
          </ul>
        </article>
      </aside>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Mathema Contest. Developed by ....</p>
    <p>Follow us: <a href="#">Facebook</a> | <a href="#">Instagram</a></p>
  </footer>
</body>
</html>
