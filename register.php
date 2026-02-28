<!DOCTYPE html>
<?php
session_start();
require_once "pdo.php";
$alreadyPaid = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    $paymentType = $_POST['category'] ?? '';
    $date = $_POST['date'] ?? '';
    $type = $_POST['type'] ?? '';
    $transactionId = $_POST['transaction_id'] ?? null;
    $_SESSION['transactionId'] = $transactionId;

    $email = $_POST['email'] ?? '';
    $member_names = [];
    $member_birthdates = [];
    $member_ages = [];

    if (!empty($email) && !empty($type)) {
        $alreadyPaid = isAlreadyPaid($pdo, $email, $type);
    }

    if (!$alreadyPaid && empty($transactionId)) {
        $_SESSION['message'] = "Please complete payment before registering.";
        header("Location: register.php");
        exit;
    }

    for ($i = 1; $i <= 4; $i++) {
        $nameField = 'member' . $i;
        $dateField = 'date' . $i;
        $ageField = 'age' . $i;

        $member_names[$i] = $_POST[$nameField] ?? '';
        $member_birthdates[$i] = $_POST[$dateField] ?? null;
        $member_ages[$i] = isset($_POST[$ageField]) ? (int)$_POST[$ageField] : null;
    }

    if($confirmPassword != $password){
        $_SESSION['message'] = "Password is not the same!";
        header("Location: register.php");
        exit;
    } elseif (strpos($_POST['email'], '@') === false) {
        $_SESSION['message'] = "Email must have an at-sign (@)";
        header("Location: register.php");
        exit;
    }else {
        if ($type == "individual" && strlen($username) < 1) {
            $_SESSION['message'] = "Please fill in your name.";
            header("Location: register.php");
            exit;
        }
        
    if ($type == "individual") {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $age = null;
        
        if (!empty($date)) {
            $birthDate = new DateTime($date);
            $today = new DateTime();
            $age = $today->diff($birthDate)->y;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, category, birthday, age, transaction_id, email)
                VALUES (:username, :password, :category, :birthday, :age, :transaction_id, :email)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword,
                ':category' => $paymentType,
                ':birthday' => $date,
                ':age' => $age,
                ':transaction_id' => $_SESSION['transactionId'],
                ':email' => $email
            ]);
            sendPaymentNotification($_SESSION['transactionId']);

            $_SESSION['message'] = "Registration successful!";
            header("Location: register.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['message'] = "Username already exists.";
            } else {
                $_SESSION['message'] = "Error: " . $e->getMessage();
            }
            header("Location: register.php");
            exit;
        }
    }
    else if ($type === "team") {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        foreach ($member_birthdates as $i => $birthdate) {
            if (empty($birthdate)) {
                $_SESSION['message'] = "Please fill in birthday for all team members.";
                header("Location: register.php");
                exit;
            }
        }
        $stmt = $pdo->prepare("SELECT 1 FROM teams WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        if ($stmt->fetchColumn()) {
            $_SESSION['message'] = "This email has already been used to register a team.";
            header("Location: register.php");
            exit;
        }

        try {
            $finalTeam = $pdo->prepare("INSERT INTO teams (email, password, category, transaction_id) VALUES (:email, :password, :category, :transaction_id)");
            $finalTeam->execute([
                ':email' => $email,
                ':password' => $hashedPassword,
                ':category' => $paymentType,
                ':transaction_id' => $_SESSION['transactionId']
            ]);
            $teamId = $pdo->lastInsertId();

            $tmb = $pdo->prepare("INSERT INTO team_members (team_id, member_name, birthday, age)
                                VALUES (:team_id, :member_name, :birthday, :age)");

            foreach ($member_names as $i => $member) {
                if (strlen(trim($member)) === 0) continue;

                $tmb->execute([
                    ':team_id' => $teamId,
                    ':member_name' => $member,
                    ':birthday' => $member_birthdates[$i],
                    ':age' => $member_ages[$i]
                ]);
            }

            sendPaymentNotification($_SESSION['transactionId']);

            $_SESSION['message'] = "Team registration successful!";
            header("Location: register.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $_SESSION['message'] = "Error during team registration: " . $e->getMessage();
            } else {
                $_SESSION['message'] = "Error: " . $e->getMessage();
            }
            header("Location: register.php");
            exit;
        }
    }
    }   
}
function isAlreadyPaid(PDO $pdo, string $email, string $type): bool {
    if ($type === "team") {
        $stmt = $pdo->prepare("SELECT transaction_id FROM teams WHERE email = :email AND transaction_id IS NOT NULL LIMIT 1");
    } else {
        $stmt = $pdo->prepare("SELECT transaction_id FROM users WHERE email = :email AND transaction_id IS NOT NULL LIMIT 1");
    }
    $stmt->execute([':email' => $email]);
    return $stmt->fetchColumn() !== false;
}
function sendPaymentNotification($transactionId) {
    if (!$transactionId) return;

    $url = 'http://localhost/coursera1st/mathemaContest/payment_notification.php';
    $data = http_build_query(['transaction_id' => $transactionId]);

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => $data,
        ]
    ];
    $context  = stream_context_create($options);
    file_get_contents($url, false, $context); 
}
?>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/logStyle.css">
    <script src="https://www.paypal.com/sdk/js?client-id=ASkE6u2TsEJFIAIvscmF1zptUShzDV0h20VRPG45IbXmODY2J-Mg7aGSq2sJQLB-S1qkGNmpvO1Hyp-m&currency=USD"></script>
</head>
<body>
    <h2>Register</h2>
    <?php
    if (isset($_SESSION['message'])) {
        echo "<p class='message'>" . $_SESSION['message'] . "</p>";
        unset($_SESSION['message']);
    }
    ?>
    <?php if ($alreadyPaid): ?>
        <p style="color: green; font-weight: bold;">You have successfully paid for the contest, please complete the registration.</p>
    <?php endif; ?>
<form method="post">
    <input type="text" name="email" placeholder="Email" required>
    <input type="text" id="username" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirmPassword" placeholder="Confirm Password" required><br>

    <select name="type" id="type" required>
        <option value="" disabled selected>Select Competition Type</option>
        <option value="individual">Individual</option>
        <option value="team">Team of Four</option>
    </select><br>

    <input type="date" id="date" name="date" placeholder="MM/DD/YYYY" onblur="calculateIndividualCategory()" required>

    <input type="text" name="category" id="category" placeholder="Category will be auto-filled" required readonly><br>

    <div class="afterPayment"></div><br>

    <button class="btn" id="registerBtn">Register</button>
    <a href="welcome.html" class="btn">Home</a>

    <div id="paypal-section" style="margin-top: 20px; display: none;">
        <div id="paypal-button-container"></div>
    </div>
</form>

<script>
const paymentSelect = document.querySelector("#type");
const paymentDiv = document.querySelector(".afterPayment");
const categorySelect = document.querySelector("#category");

paymentSelect.addEventListener("change", (e) => {
    const selected = e.target.value;
    let showPayment = "";

    if (selected === "individual") {
        document.getElementById('date').style.display = "flex";
        document.getElementById('date').style.margin = "5px";
        document.getElementById('username').style.display = "flex";
        document.getElementById('username').style.margin = "8px";
        showPayment = `
            <label>Participant fee:</label>
            <input type="text" class="inputFee" id="fee" disabled value="5$">
        `;
    } else if (selected === "team") {
        document.getElementById('date').style.display = "none";
        document.getElementById('username').style.display = "none";
        showPayment = `
            <label>Member 1:</label>
            <input type="text" name="member1" required>
            <input type="date" name="date1" id="date1" onblur="calculateTeamCategory()">

            <label>Member 2:</label>
            <input type="text" name="member2" required>
            <input type="date" name="date2" id="date2" onblur="calculateTeamCategory()">

            <label>Member 3:</label>
            <input type="text" name="member3" required>
            <input type="date" name="date3" id="date3" onblur="calculateTeamCategory()">

            <label>Member 4:</label>
            <input type="text" name="member4" required>
            <input type="date" name="date4" id="date4" onblur="calculateTeamCategory()">

            <label>Participant fee:</label>
            <input type="text" class="inputFee" id="fee" disabled value="20$">

            <input type="hidden" name="age1" id="age1">
            <input type="hidden" name="age2" id="age2">
            <input type="hidden" name="age3" id="age3">
            <input type="hidden" name="age4" id="age4">
        `;
    }

    paymentDiv.innerHTML = showPayment;
});

function calculateAge(dateStr) {
    if (!dateStr) return 0;
    const birthDate = new Date(dateStr);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
    return age;
}

function calculateIndividualCategory() {
    const birthdateInput = document.getElementById("date");
    const type = document.getElementById("type").value;
    if (type !== "individual") return;

    const age = calculateAge(birthdateInput.value);
    let category = "";

    if (age >= 6 && age <= 13) category = "primary";
    else if (age >= 14 && age <= 17) category = "junior";
    else if (age >= 18 && age <= 21) category = "senior";
    else if (age >= 14 && age <= 50) category = "open";

    categorySelect.value = category || "";
}

function calculateTeamCategory() {
    const dates = [
        document.querySelector('input[name="date1"]')?.value,
        document.querySelector('input[name="date2"]')?.value,
        document.querySelector('input[name="date3"]')?.value,
        document.querySelector('input[name="date4"]')?.value
    ];

    const ageInputs = [
        document.querySelector('#age1'),
        document.querySelector('#age2'),
        document.querySelector('#age3'),
        document.querySelector('#age4')
    ];

    const ages = dates.map((d, i) => {
        const age = calculateAge(d);
        if (age > 0 && ageInputs[i]) ageInputs[i].value = age;
        return age;
    }).filter(a => a > 0);

    if (ages.length === 0) return;

    const oldest = Math.max(...ages);
    let category = "";

    if (oldest >= 6 && oldest <= 13) category = "primary";
    else if (oldest >= 14 && oldest <= 17) category = "junior";
    else if (oldest >= 18 && oldest <= 21) category = "senior";
    else if (oldest >= 14 && oldest <= 50) category = "open";


    categorySelect.value = category || "";
}

paypal.Buttons({
    createOrder: function(data, actions) {
        const type = document.getElementById("type").value;
        const amount = (type === "team") ? "20.00" : "5.00";
        return actions.order.create({
            purchase_units: [{ amount: { value: amount } }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            fetch('payment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(details)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "transaction_id";
                    input.value = data.transaction_id;
                    document.querySelector("form").appendChild(input);
                    document.getElementById("registerBtn").disabled = true;
                    document.querySelector("form").submit();
                } else {
                    alert("Payment completed but saving failed.");
                }
            });
        });
    }
}).render('#paypal-button-container');

function checkEmailAndTypeFilled() {
    const alreadyPaid = <?= $alreadyPaid ? 'true' : 'false' ?>;
    const email = document.querySelector('input[name="email"]').value.trim();
    const type = document.querySelector('#type').value;
    const paypalSection = document.getElementById("paypal-section");

    if (alreadyPaid) {
        paypalSection.style.display = "none";
        document.getElementById("registerBtn").disabled = false; 
        return;
    }

    if (email !== '' && type !== '') {
        paypalSection.style.display = "block";
    } else {
        paypalSection.style.display = "none";
    }
}

document.querySelector('input[name="email"]').addEventListener("input", checkEmailAndTypeFilled);
document.querySelector('#type').addEventListener("change", checkEmailAndTypeFilled);

</script>
</body>
</html>
