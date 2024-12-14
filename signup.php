<?php
include "service/database.php";

$register_message = '';

if (isset($_POST['signup'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($db, $_POST['confirm-password']);

    if ($password !== $confirm_password) {
        $register_message = "Password dan konfirmasi password tidak cocok.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $check_query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $check_query);
        
        if (mysqli_num_rows($result) > 0) {
            $register_message = "Email sudah digunakan.";
        } else {

            $query = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";
            if (mysqli_query($db, $query)) {
                $register_message = "Pendaftaran berhasil. Silakan login.";
            } else {
                $register_message = "Terjadi kesalahan: " . mysqli_error($db);
            }
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
</head>
<body>
    
    <?php include "layout/html/signup.html" ?>

</body>
</html>
