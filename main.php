<?php
include "service/database.php"; 

$query = "SELECT p.post_id, p.title, p.description, p.created_at, u.username, u.profile_picture 
          FROM post p 
          INNER JOIN users u ON p.user_id = u.user_id 
          ORDER BY p.created_at DESC";
$result = $db->query($query);
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EHT</title>
        <link rel="stylesheet" href="layout/css/post-card.css">
    </head>

    <body style="padding: 65px 0px 0px 340px;
    background-color: rgba(255, 244, 234, 1);">
        
        <?php include "layout/html/header.html" ?>
        <?php include "layout/html/sidebar.html" ?>
        <?php include "layout/html/sub-sidebar1.html" ?>
        <?php include "layout/html/sub-sidebar2.html" ?>
        <div class="post-container">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($post = $result->fetch_assoc()): ?>
            <a class="post-card" href="comment.php?post_id=<?php echo $post['post_id']; ?>">
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
                        <p class="number">0</p>
                    </button>
                    <button class="share-button">
                        <img class="share-icon" src="layout/Icon/share.svg">
                        <p class="share-text">share</p>
                    </button>
                </div>
            </a>
        <?php endwhile; ?>
    <?php else: ?>
        <p>Tidak ada postingan untuk ditampilkan.</p>
    <?php endif; ?>

    </body>

</html>