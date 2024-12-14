<?php
session_start();
include "service/database.php";

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;


$query_post = "SELECT p.title, p.description, p.created_at, u.username, u.profile_picture,
                      (SELECT COUNT(*) FROM comments c WHERE c.post_id = p.post_id) AS comment_count
               FROM post p
               INNER JOIN users u ON p.user_id = u.user_id
               WHERE p.post_id = ?";

$stmt_post = $db->prepare($query_post);
$stmt_post->bind_param("i", $post_id);
$stmt_post->execute();
$result_post = $stmt_post->get_result();
$post = $result_post->fetch_assoc();


if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk memberikan komentar.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $comment = trim($_POST['comment']);

    if (empty($post_id) || empty($comment)) {
        $error_message = "Komentar tidak boleh kosong.";
    } else {
        $query = "INSERT INTO comments (post_id, user_id, comment, created_at) VALUES (?, ?, ?, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iis", $post_id, $user_id, $comment);

        if ($stmt->execute()) {
            header("Location: comment.php?post_id=$post_id");
            exit();
        } else {
            $error_message = "Gagal menyimpan komentar.";
        }
    }
}

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

$query_comments = "SELECT c.comment, c.created_at, u.username, u.profile_picture
                   FROM comments c
                   INNER JOIN users u ON c.user_id = u.user_id
                   WHERE c.post_id = ?
                   ORDER BY c.created_at DESC";
$stmt_comments = $db->prepare($query_comments);
$stmt_comments->bind_param("i", $post_id);
$stmt_comments->execute();
$result_comments = $stmt_comments->get_result();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link rel="stylesheet" href="layout/css/comment.css">
    <link rel="stylesheet" href="layout/css/post-card.css">
    
</head>
<body style="padding: 65px 0px 0px 340px;
             overflow-y: scroll;
             background-color: rgba(255, 244, 234, 1);">
    <?php include "layout/html/header-logged.html" ?>
    <?php include "layout/html/sidebar-logged.html" ?> 
    <?php include "layout/html/comment.html" ?> 

    <div class="post-detail">
        <?php if ($post): ?>
                <div class="profile-post">
                    <img class="profile-picture"
                        src="<?php echo !empty($post['profile_picture']) ? htmlspecialchars($post['profile_picture']) : 'layout/images/default-profile.png'; ?>"
                        alt="Profile Picture">
                    <div class="username">
                        <?php echo htmlspecialchars($post['username']); ?>
                    </div>
                </div>
                <div class="judul-post">
                    <p class="judul-text"><?php echo htmlspecialchars($post['title']); ?></p>
                </div>
                <div class="description-post">
                    <p class="deskripsi-text"><?php echo nl2br(htmlspecialchars($post['description'])); ?></p>
                </div>
                <div class="status-post">
                    <div class="vote-button">
                        <button class="up-button">
                            <img class="up-icon" src="layout/Icon/up.svg">
                        </button>
                        <button class="down-button">
                            <img class="down-icon" src="layout/Icon/down.svg">
                        </button>
                    </div>
                    <button class="comment-button">
                        <img class="comment-icon" src="layout/Icon/comment.svg">
                        <p class="number"><?php echo htmlspecialchars($post['comment_count']); ?></p>

                    </button>
                    <button class="share-button">
                        <img class="share-icon" src="layout/Icon/share.svg">
                        <p class="share-text">share</p>
                    </button>
                </div>
        <?php else: ?>
            <p>Postingan tidak ditemukan.</p>
        <?php endif; ?>
    </div>

    <div class="comment-input">
        <?php if (isset($error_message)): ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <textarea style="padding: 10px;" class="comment-text" name="comment" placeholder="Tulis komentar Anda..." required></textarea>
            <input type="hidden" name="post_id" value="<?php echo htmlspecialchars($post_id); ?>">
            <button class="status-button" type="submit">Simpan</button>
        </form>
    </div>

<div class="comments-list">
    <?php while ($comment = $result_comments->fetch_assoc()): ?>
        <div class="profile">
            <img class="photo-profile" src="<?php echo !empty($comment['profile_picture']) ? htmlspecialchars($comment['profile_picture']) : 'layout/Profile/default-profile.jpg'; ?>" alt="Profile Picture">
            <p class="username-profile"><strong><?php echo htmlspecialchars($comment['username']); ?></strong></p>
        </div>
        <p class="user-comment"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
    <?php endwhile; ?>
</div>

</body>
</html>