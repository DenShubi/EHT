<?php
session_start();
include "service/database.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($db, $_POST['title']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

    $query = "INSERT INTO post (user_id, title, description) 
              VALUES ('$userId', '$title', '$description')";

    if ($db->query($query)) {
        echo "Postingan berhasil disimpan!";
        header("Location: main-logged.php"); 
        exit();
    } else {
        echo "Error: " . $db->error;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "layout/html/header-logged.html" ?>
    <?php include "layout/html/sidebar-logged.html" ?>
    <?php include "layout/html/create-post.html" ?>

</body>
</html>