<?php
require("../includes/db_connect.php");
?>

<?php
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
try {
    $stmt = $conn->prepare("SELECT * FROM file_uploads");
    $stmt->execute();

    $result = $stmt->get_result();
} catch (mysqli_sql_exception) {
    echo "sqli error again";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>

    <dialog id="comment_dialog"> <!--comment dialog box-->

        <img src="" alt="" id="image_display">

        <form action="comment.php" method="post">
            <input type="hidden" name="post_id" id="post_id" value=""> <!--hidden input to store post ID, look up in the script below-->
            <label for="comment_input">Enter your comment:</label><br>
            <textarea id="comment_input" name="comment_input"></textarea>
            <button type="submit">Submit</button>
        </form>

        <button onclick=" this.parentElement.close()">Close</button> <!--close this element's parent element-->

    </dialog>

    <header>
        <?php require('../includes/header.php'); ?> <!--newer and stricter version of include(" file_name");-->
    </header>

    <main>

        <?php while ($row = $result->fetch_assoc()) {

            $postID = (int) $row['post_ID'];
            $filePath = (string) $row['filePath'];

        ?>
            <div class="post_container"><!--container that makes the post card be place on the center-->
                <div class="post_card"><!--the post card-->
                    <span class="post-date"><?= $row['post_date'] ?></span> <!--post date-->
                    <img src="<?= htmlspecialchars($row['filePath']) ?>" alt="<?= htmlspecialchars($row['fileName']) ?>" id="post_images"> <!--image display-->
                    <br>
                    <div class=" caption"><!--caption section-->
                        <?= $row['caption']; ?>
                    </div>

                    <div class="like_and_comment_container"> <!--like and comment section-->
                        <button class="like_button">Like</button>
                        <button class="comment_button" onclick="openCommentDialog(<?= (int)$postID ?>, '<?= htmlspecialchars($filePath, ENT_QUOTES, 'UTF-8') ?>')">Comment</button>
                    </div>

                </div>
            </div>
        <?php } ?>

    </main>

    <footer>
        <?php require('../includes/footer.html'); ?>
    </footer>
</body>

<script>
    const commentDialog = document.getElementById('comment_dialog'); // Get the comment dialog element

    const commentButtons = document.querySelectorAll('.comment_button'); // Get all comment buttons
    const likeButtons = document.querySelectorAll('.like_button'); // Get all like buttons

    const postID = document.getElementById('post_id'); // Hidden input to store post ID

    function openCommentDialog(id, filePath) { // Function to open comment dialog with post ID nad file path.
        postID.value = id;
        document.getElementById("image_display").src = filePath;
        commentDialog.showModal();
    }

    likeButtons.forEach(button => { // Add click event listener to each like button
        button.addEventListener('click', function() {
            alert('You liked this post!');
        });
    });

    commentButtons.forEach(button => { // Add click event listener to each comment button
        button.addEventListener('click', () => {
            commentDialog.showModal();
        });
    });
</script>

</html>