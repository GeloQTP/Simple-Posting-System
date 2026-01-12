<?php
require("../includes/db_connect.php");
?>

<?php
mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);
try {
    $stmt = $conn->prepare("SELECT * FROM file_uploads ORDER BY post_date DESC");
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

        <div class="comment_content_dialogue_container"> <!--flex container to hold image preview and comment form-->

            <div class="image_preview_container"> <!--image preview and comment form container-->

                <img src="" alt="" id="image_preview"> <!--image preview-->

                <form> <!--no action or method needed since it's handled by JavaScript -->
                    <input type="hidden" name="post_id" id="post_id" value=""> <!--hidden input to store post ID, look up in the script below-->
                    <label for="comment_input">Enter yourcomment:</label><br>
                    <textarea id="comment_input" name="comment_input"></textarea>
                    <button type="submit">Submit</button>
                </form>

                <script>
                    document.addEventListener('DOMContentLoaded', () => { // Ensure the DOM is fully loaded before attaching event listeners.

                        const commentForm = document.querySelector('#comment_dialog form');

                        commentForm.addEventListener('submit', async (e) => { // the 'e' is taken from the event listener which is the commentForm.
                            e.preventDefault(); // Prevent default form submit to allow AJAX posting.

                            const form = e.currentTarget;
                            const formData = new FormData(form); // Create FormData object from the form, gets all input values inside the form.

                            try {
                                const res = await fetch('comment.php', { // this function posts the comment to comment.php and stores it in the database
                                    method: 'POST',
                                    body: formData,
                                    credentials: 'same-origin'
                                });

                                console.log(res);

                                if (!res.ok) throw new Error('Network response was not ok'); // Check for HTTP errors.

                                // Clear textarea and reload comments after successful post
                                document.getElementById('comment_input').value = '';
                                const postId = document.getElementById('post_id').value; // Get the current post ID and send it to loadComments function
                                if (postId) loadComments(postId); // Reload comments for the post.
                            } catch (err) {
                                console.error('Error posting comment', err);
                                alert('Could not post comment');
                            }

                        });

                    });
                </script>

            </div>

            <div class="comments_container"> <!--comments display container-->
                <div id="comments_display">
                    <!-- Comments will be loaded here -->
                </div>
            </div>

        </div>

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
    const commentDialog = document.getElementById('comment_dialog'); // Get the comment dialog element.

    const likeButtons = document.querySelectorAll('.like_button'); // Get all like buttons.

    const postID = document.getElementById('post_id'); // Hidden input to store post ID.

    const openCommentDialog = (id, filePath) => { // Function to open comment dialog with post ID nad file path.
        postID.value = id;
        document.getElementById("image_preview").src = filePath;
        commentDialog.showModal();
        loadComments(id); // Load comments for the post when dialog opens
    };

    likeButtons.forEach(button => { // Add click event listener to each like button
        button.addEventListener('click', () => {
            alert('You liked this post!!');
        });
    });

    // Utility to escape HTML to avoid XSS when inserting comments
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>"']/g, (c) => {
            return {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": "&#39;"
            } [c];
        });
    }

    // Load comments for a post and render them in the dialog
    async function loadComments(postId) {

        const display = document.getElementById('comments_display'); // not really used but could be useful while waiting for comments to load
        display.innerHTML = 'Loading comments...';

        try {

            const res = await fetch(`comment.php?post_id=${encodeURIComponent(postId)}`, { // Fetch comments via GET no method needed because GET is default
                credentials: 'same-origin' // Include cookies and authentication headers so the server can identify the user
            });

            if (!res.ok) throw new Error('Network error'); // Check for HTTP errors.

            const comments = await res.json(); // get the JSON response containing comments

            if (!Array.isArray(comments) || comments.length === 0) { // check if comments is an array and has elements if not display no comments yet
                display.innerHTML = '<p>No comments yet.</p>';
                return;
            }

            const html = comments.map(c => {
                const when = c.commentDate ? escapeHtml(c.commentDate) : '';
                const who = c.userID ? 'User ' + escapeHtml(String(c.userID)) : 'Anonymous';
                const text = escapeHtml(c.comment);
                return `<div class= "comment">
                            <strong>${who}</strong>
                            <small class = "comment_date">${when}</small>
                            <div>${text}</div>
                        </div>`;
            }).join('');

            display.innerHTML = html;

        } catch (err) {
            console.error('Error loading comments', err);
            display.innerHTML = '<p>Could not load comments.</p>';
        }
    }
</script>

</html>