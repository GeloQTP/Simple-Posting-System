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

    <div>

    </div>

    <header>
        <?php require('../includes/header.php'); ?> <!--newer and stricter version of include("file_name");-->
    </header>

    <main>
        <?php while ($row = $result->fetch_assoc()) {
        ?>
            <div class="post_container"><!--container that makes the post card be place on the center-->
                <div class="post_card"><!--the post card-->
                    <span class="post-date"><?= $row['post_date'] ?></span> <!--post date-->
                    <img src="<?= htmlspecialchars($row['filePath']) ?>" alt="<?= htmlspecialchars($row['fileName']) ?>" id="post_images"> <!--image display-->
                    <br>
                    <div class="caption"><!--caption section-->
                        <?= $row['caption']; ?>
                    </div>
                </div>
            </div>
        <?php } ?>

    </main>

    <footer>
        <?php require('../includes/footer.html'); ?>
    </footer>
</body>

<script src="script.js"></script>

</html>