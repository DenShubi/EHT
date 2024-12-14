<?php 
session_start();
include "service/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $db->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];

            $_SESSION['profile_picture'] = !empty($user['profile_picture']) 
                ? $user['profile_picture'] 
                : 'layout/Profile/default-profile.jpg';

            header("Location: main-logged.php");
            exit();
        } else {
            
        }
    } else {
        
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>
    <?php include "layout/html/login.html" ?>
</body>
</html>
