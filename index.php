<?php
session_start();
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Mathema Contest 2025</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/headerStyle.css" />
  </head>
  <body>
    <header>
      <div class="leftSide">
        <div class="logo">
          <a href="index.php">
            <img class="logoImg" src="images/logo.png" alt="Logo" /> MC
          </a>
        </div>
      </div>
      <div class="rightSide">
        <nav>
          <ul class="navList">
            <li class="navigateEle"><a href="welcome.html">Welcome</a></li>
            <li class="navigateEle"><a href="contact.html">Contact</a></li>
            <li class="dropdown">
              <img src="images/more.png" alt="More Menu" />
              <ul class="dropdown-menu">
                <li><a href="category.html">Category</a></li>
                <li><a href="Forum/forum.php">Forum</a></li>
                <li><a href="info.html">Detail</a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<li><a href="dashboard/dashboard.php">Dashboard</a></li>';
                }
                ?>
                <?php
                if (isset($_SESSION['username'])) {
                    echo '<li><a href="logout.php">Logout</a></li>';
                }
                ?>
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <div class="headerPic">
        <h1>Welcome to Mathema Contest 2025</h1>
        <img src="images/children.jpeg" alt="Mathema Children" />
      </div>

      <div class="bodyContent">
        <div class="introBox">
          <p>
            Mathema Contest is a global online math competition proudly based in
            South Africa. It aims to spark a love for mathematics, empower young
            minds, and highlight the importance of math education across the
            world—preparing the next generation with the skills they need to
            thrive.
          </p>
        </div>
        <div class="clockDiv">
          <div class="clockLbl"><p>Days until Competition 2</p></div>
          <div class="clockTime"></div>
        </div>

        <div class="cateBackground">
          <h2>Categories</h2>

          <div class="toggleTab">
            <button id="individualBtn" class="tabBtn active">Individual</button>
            <button id="teamBtn" class="tabBtn">Team</button>
          </div>
          <div class="borderCategory">
            <div class="toggleSubtitle" id="categoryNotice">
              NO LIMIT TO NUMBER OF PARTICIPANTS
            </div>

            <div class="cateWrapper">
              <div class="categoryBox">
                <p>Primary<br /><strong>Grades 1–7</strong></p>
              </div>
              <div class="categoryBox">
                <p>Junior<br /><strong>14–17 years</strong></p>
              </div>
              <div class="categoryBox">
                <p>Senior<br /><strong>18–21 years</strong></p>
              </div>
              <div class="categoryBox">
                <p>Open<br /><strong>14–50 years</strong></p>
              </div>
            </div>
          </div>
        </div>

        <div class="info-box">
          <h2><strong>Registration Deadlines</strong></h2>
          <ul>
            <li>Competition 1: 19 March 2025</li>
            <li>Competition 2: 13 August 2025</li>
          </ul>
        </div>

        <div class="scheduleSection">
          <h2>Schedule</h2>

          <div class="scheduleGroup">
            <div class="scheduleTitles">
              <h3>Competition 1</h3>
              <h3>Competition 2</h3>
            </div>
            <div class="scheduleGrid">
              <div class="scheduleBox">
                <p>March – June 2025</p>
                <p>Deadline for registrations: 19 March</p>
              </div>
              <div class="scheduleBox">
                <p>August – November 2025</p>
                <p>Deadline for registrations: 13 August</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <section class="extras">
        <h2>Why Join Mathema Contest?</h2>
        <ul>
          <li>Win cash prizes</li>
          <li>Get a free Mathema T-shirt</li>
          <li>Learnership & internship opportunities</li>
          <li>Online Mathema Conference (Free for all participants)</li>
        </ul>
      </section>
    </main>

    <footer>
      <p>&copy; 2025 Mathema Contest. Developed by ....</p>
      <p>Follow us: <a href="#">Facebook</a> | <a href="#">Instagram</a></p>
    </footer>

    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const deadline = new Date("2025-11-03T00:00:00");
        const clockElement = document.querySelector(".clockTime");

        function updateCountdown() {
          const now = new Date();
          const diff = deadline - now;

          if (diff <= 0) {
            clockElement.textContent = "Registration Closed";
            return;
          }

          const weeks = Math.floor(diff / (1000 * 60 * 60 * 24 * 7));
          const days = Math.floor((diff / (1000 * 60 * 60 * 24)) % 7);
          const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
          const minutes = Math.floor((diff / (1000 * 60)) % 60);
          const seconds = Math.floor((diff / 1000) % 60);

          clockElement.innerHTML = `
          <div><span class="num">${weeks}</span><br><span class="label">Weeks</span></div>
          <div><span class="num">${days}</span><br><span class="label">Days</span></div>
          <div><span class="num">${hours}</span><br><span class="label">Hours</span></div>
          <div><span class="num">${minutes}</span><br><span class="label">Min</span></div>
          <div><span class="num">${seconds}</span><br><span class="label">Sec</span></div>
        `;
        }

        const teamBtn = document.getElementById("teamBtn");
        const individualBtn = document.getElementById("individualBtn");
        const categoryNotice = document.getElementById("categoryNotice");

        teamBtn.addEventListener("click", () => {
          teamBtn.classList.add("active");
          individualBtn.classList.remove("active");
          categoryNotice.textContent =
            "EACH TEAM/GROUP IS LIMITED TO ONLY 4 LEARNERS";
        });

        individualBtn.addEventListener("click", () => {
          individualBtn.classList.add("active");
          teamBtn.classList.remove("active");
          categoryNotice.textContent = "NO LIMIT TO NUMBER OF PARTICIPANTS";
        });

        updateCountdown();
        setInterval(updateCountdown, 1000);
      });
    </script>
  </body>
</html>
