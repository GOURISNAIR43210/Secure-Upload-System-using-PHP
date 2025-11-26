<?php
session_start();
require 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if ($username && $password) {
        $stmt = $conn->prepare("SELECT id, password_hash FROM user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $hash);
            $stmt->fetch();

            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $id;
                header("Location: upload.php");
                exit;
            } else {
                $message = "Invalid password!";
            }
        } else {
            $message = "User not found!";
        }
    }
}
?>
<html>
<head>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include "navbar.php"; ?>

<div class="box">
    <h2>Login</h2>

    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
