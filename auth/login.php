<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Locate user folder based on email
    $usersDir = "users/";
    $userFolder = null;
    
    foreach (scandir($usersDir) as $folder) {
        if ($folder !== "." && $folder !== "..") {
            $userFile = "$usersDir$folder/user.json";
            if (file_exists($userFile)) {
                $userData = json_decode(file_get_contents($userFile), true);
                if ($userData['email'] === $email) {
                    $userFolder = $folder;
                    break;
                }
            }
        }
    }

    if ($userFolder) {
        $userFile = "$usersDir$userFolder/user.json";
        $userData = json_decode(file_get_contents($userFile), true);

        if (password_verify($password, $userData['password'])) {
            $_SESSION['user'] = $userFolder; // Store folder name, not full user data
            header("Location: ../profile.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/index.css">
    <link rel="stylesheet" href="../css/login.css">
    <title>Palbites</title>
</head>
<body>
    <nav>
        <div class="nav-bar">
            <div class="nav-image">
                <a href="index"><img src="../images/logo.png" alt="Palbites logo" class="nav-logo"></a>
            </div>
            <div class="nav-links">
                <ul class="nav-ul">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="signup.php">Sign Up</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="login">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <label for="">Email</label>
            <input type="email" name="email" placeholder="Email" value="<?= $_COOKIE['user_email'] ?? '' ?>" required>
            <label for="">Password</label>
            <input type="password" name="password" placeholder="Password" required>
            <label><input type="checkbox" name="remember"> Remember Me</label>
            <button type="submit">Login</button>
            <a href="forgot_password.php">Forgot Password?</a>
            <h4>Need an account?<a href="signup.php">Sign Up</a></h4>
        </form>
    </div>

    <script src="../js/nav.js"></script>
</body>
</html>
