<?php
session_start();

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    die("Unauthorized access!");
}

$userFolder = "users/" . $_SESSION['user'];
$userFile = "$userFolder/user.json";

// Check if user file exists
if (!file_exists($userFile)) {
    die("User data not found!");
}

// Get existing user data
$userData = json_decode(file_get_contents($userFile), true);

// Debug: Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (empty($_POST)) {
        die("No POST data received!");
    }

    // Sanitize and update fields
    $updated = false; // Track if data is updated

    function updateField(&$userData, $postKey, $jsonKey) {
        global $updated;
        if (isset($_POST[$postKey]) && $_POST[$postKey] !== $userData[$jsonKey]) {
            $userData[$jsonKey] = $_POST[$postKey];
            $updated = true;
        }
    }

    updateField($userData, 'email', 'email');
    updateField($userData, 'contact', 'phone');
    updateField($userData, 'address', 'address');
    updateField($userData, 'province', 'province');
    updateField($userData, 'city', 'city');

    // Force update if password is entered
    if (!empty($_POST['password'])) {
        $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $updated = true;
    }

    // Save only if data has changed
    if ($updated) {
        if (file_put_contents($userFile, json_encode($userData, JSON_PRETTY_PRINT))) {
            header("Location: ../profile.php?update=success");
            exit;
        } else {
            die("Error saving updated data!");
        }
    } else {
        header("Location: ../profile.php?update=nochange");
        exit;
    }
} else {
    die("Invalid request!");
}
?>
