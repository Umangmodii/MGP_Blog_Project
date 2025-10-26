<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once __DIR__ . '/../Backend/Database/connection.php';
    if (!$conn) {
        $error = "Database connection failed!";
    } else {
        $valid_email = 'admin@gmail.com';
        $valid_password = 'admin123';

        $input_email = isset($_POST["email"]) ? trim($_POST["email"]) : '';
        $input_password = isset($_POST["password"]) ? $_POST["password"] : '';

        if ($input_email === $valid_email && $input_password === $valid_password) {
            $_SESSION['user_email'] = $valid_email;
            header("Location: /../MGP_Blog_Project/Backend/index.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Electronica - Electrical Services</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/global.css" rel="stylesheet">
	<link href="css/index.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
	<link href="https://fonts.googleapis.com/css?family=Alata&display=swap" rel="stylesheet">
	<script src="js/jquery-2.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<?php
    include 'header.php';
?>

<div class="login-container">
    <div class="login-title">Login</div>
    <?php if (!empty($error)): ?>
        <div style="color: #d32f2f; margin-bottom: 16px; text-align:center;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>
    <form class="login-form" action="" method="post" autocomplete="off">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" required placeholder="Enter your email"
              value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

        <label for="password" style="margin-top: 10px;">Password</label>
        <input type="password" id="password" name="password" required placeholder="Enter your password">

        <button type="submit">Login</button>
    </form>
</div>

<?php
    include 'footer.php';
?>

</body>
</html>