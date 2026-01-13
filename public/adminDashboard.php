<?php
session_start();
require("../includes/db_connect.php");
require("../classes/DashboardStats.php");
?>
<?php

$dashBoardStats = new DashboardStats($conn);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$stats = new DashboardStats($conn);

$statement = $conn->prepare("SELECT * FROM users");
$statement->execute();

$result = $statement->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/adminDashboard.css">
</head>

<body>

    <div>

    </div>

    <header>
        <?php require("../includes/adminHeader.php"); ?> <!--newer and stricter version of include("file_name");-->
    </header>

    <main>

        <div class="container">
            <div class="crud">
                <h1>Admin Control Panel</h1>
                <br>
                <label for="search_user">Search User:</label>
                <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                    <input type="text" id="search_user" name="search_user" value="<?= htmlspecialchars($_POST["search_user"] ?? '') ?>">
                    <input type="submit" id="search_btn" value="Search">
                </form>

                <br>

                <form action="fileUpload.php" method="post" enctype="multipart/form-data">
                    <label for="file_upload">File Upload:</label> <br>
                    <input type="file" name="file_upload" id="file_upload" required><br><!--file upload input-->
                    <input type="text" name="upload_caption"> <br> <!--upload caption-->
                    <input type="submit" value="Upload File" id="upload_button">
                </form>

                <br>

            </div>

            <div class="table">
                <h1>Welcome to the Admin Dashboard</h1>
                <br>
                <div style="display: flex; gap:1rem;">
                    <span>Total Users: <?= $result->num_rows ?></span>
                    <span>Total Admins: <?= $dashBoardStats->totalAdmins(); ?></span>
                    <span> New Users Today: <?= $dashBoardStats->newUsersToday(); ?></span>
                    <span>New Users this Month: <?= $dashBoardStats->newUsersThisMonth(); ?></span>
                </div>

                <table border="1" cellspacing="1" width="100%" bgcolor="#f2f2f2" style="text-align: center;" id="userTable">
                    <tr>
                        <th>Username</th>
                        <th>Registration Date</th>
                        <th>User Type</th>
                    </tr>

                    <?php

                    $user_filter = isset($_POST['search_user']) ? $_POST['search_user'] : ''; // checks if there's a value in the input named search

                    while ($row = $result->fetch_assoc()) { // while the $rows still getting rows from $result->fetch_assoc(), loop
                        if ($row["username"] !== $user_filter) {
                            continue;
                        }
                    ?>
                        <tr class="table-row">
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['reg_date']) ?></td>
                            <td><?= htmlspecialchars($row['userType'] ?? 'user') ?></td>
                            <td><span>X</span></td>
                        </tr>

                    <?php
                    }
                    ?>

                </table>
            </div>

        </div>

    </main>

    <footer>
        <?php require("../includes/footer.html"); ?>
    </footer>
</body>

<script src="script.js"></script>

</html>