<?php 
session_start();

include "service/database.php";

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

$queryProfile = "SELECT username, email, description, profile_picture, cover_photo FROM users WHERE user_id = $userId";
$resultProfile = $db->query($queryProfile);

if (!$resultProfile) {
    die("Error saat mengambil data pengguna: " . $db->error);
}

if ($resultProfile->num_rows > 0) {
    $profileData = $resultProfile->fetch_assoc();
} else {
    die("Data pengguna tidak ditemukan.");
}

if (isset($_POST['save'])) {
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $profilePicture = $profileData['profile_picture'];
    $coverPhoto = $profileData['cover_photo'];

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
        $profilePicture = "uploads/" . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profilePicture);

        $_SESSION['profile_picture'] = $profilePicture;
    }

    if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['size'] > 0) {
        $coverPhoto = "uploads/" . basename($_FILES['cover_photo']['name']);
        move_uploaded_file($_FILES['cover_photo']['tmp_name'], $coverPhoto);
    }

    $sql = "UPDATE users SET 
            description = '$description', 
            profile_picture = '$profilePicture',
            cover_photo = '$coverPhoto',
            updated_at = NOW()
            WHERE user_id = $userId";

    if ($db->query($sql)) {
        header("Location: profile.php"); 
        exit();
    } else {
        echo "Gagal menyimpan data: " . $db->error;
    }

    echo "Foto profil disimpan di: " . $_SESSION['profile_picture'];
exit();

}

if (isset($_POST['delete_account'])) {

    $db->begin_transaction();

    try {
        $deletePostsQuery = "DELETE FROM post WHERE user_id = $userId";
        $db->query($deletePostsQuery);

        $deleteUserQuery = "DELETE FROM users WHERE user_id = $userId";
        $db->query($deleteUserQuery);

        $db->commit();

        session_destroy();
  
        header("Location: login.php?message=Akun Anda telah berhasil dihapus.");
        exit();
    } catch (Exception $e) {

        $db->rollback();

        echo "Gagal menghapus akun: " . $e->getMessage();
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
        <?php include "layout/html/profile.html" ?>

</body>
</html>