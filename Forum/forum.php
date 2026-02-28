<!DOCTYPE html>
<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM user_profiles WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
if (!$stmt->fetch()) {
    header("Location: setupHobby.php");
    exit;
}
?>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mathema Contest Forum</title>
  <link rel="stylesheet" href="css/forumCSS.css" />
  <link rel="stylesheet" href="css/headerStyle.css" />
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
        <section>
          <h2>Create a New Post</h2>
          <form action="submit-topic.php" method="POST" class="postDiv">
            <div class="avatarAndTitle">
              <img src="images/avatar.png" alt="User Avatar" />
              <input type="text" name="title" placeholder="Post title..." required />
            </div>
            <div class="postContent">
              <textarea name="message" placeholder="Write your post here..." required></textarea>
            </div>
            <button type="submit">Submit</button>
          </form>
        </section>

        <section class="postTable">
          <label for="filter">Filter by Section:</label>
          <select id="filter" class="categoryFilter">
            <option value="all">All Sections</option>
            <option value="tips">Math Tips & Tricks</option>
            <option value="prep">Contest Preparation</option>
            <option value="general">General Discussion</option>
          </select>

          <table>
            <colgroup>
              <col style="width: 40%;">
              <col style="width: 15%;">
              <col style="width: 15%;">
              <col style="width: 30%;">
            </colgroup>
            <thead>
              <tr class="headerTable">
                <th>Topics</th>
                <th>Replies</th>
                <th>Author</th>
                <th>Freshness</th>
              </tr>
            </thead>
            <?php
              $stmt = $pdo->query("SELECT t.id, t.title, t.author, COUNT(r.id) AS reply_count, MAX(t.created_at) AS freshness
              FROM topics t
              LEFT JOIN replies r ON t.id = r.topic_id
              GROUP BY t.id
              ORDER BY freshness DESC");

              while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $title = htmlspecialchars($row['title']);
                $author = htmlspecialchars($row['author']);
                $reply_count = $row['reply_count'];
                $freshness = $row['freshness'] ? date("M j, Y, g:i a", strtotime($row['freshness'])) : "No replies";

                echo "<tr>
                        <td><a href='forumTopic.php?id={$row['id']}'>$title</a></td>
                        <td><span class='badge'>$reply_count</span></td>
                        <td><strong>$author</strong></td>
                        <td><em>$freshness</em></td>
                      </tr>";
              }
            ?>
          </table>
        </section>
      </div>

      <aside class="forumSidebar">
        <article class="sidebarBox">
          <h3>User Profile</h3>
          <ul>
            <?php
            if (isset($_SESSION['user_id'])) {
                $stmt = $pdo->prepare("SELECT category, education, interests, goals FROM user_profiles WHERE user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($profile) {
                    echo "<li><strong>Category:</strong> " . htmlspecialchars($profile['category']) . "</li>";
                    echo "<li><strong>Education:</strong> " . htmlspecialchars($profile['education']) . "</li>";
                    echo "<li><strong>Interests:</strong> " . htmlspecialchars($profile['interests']) . "</li>";
                    echo "<li><strong>Goal:</strong> " . htmlspecialchars($profile['goals']) . "</li>";
                } else {
                    echo "<li><em>Your profile is not completed yet.</em></li>";
                }
            } else {
                echo "<li><em>You are not logged in.</em></li>";
            }
            ?>
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
    <p>Follow us:
      <a href="#">Facebook</a> | <a href="#">Instagram</a>
    </p>
  </footer>
</body>
</html>
