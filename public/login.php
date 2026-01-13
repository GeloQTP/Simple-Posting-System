<?php
session_start();
require("../includes/db_connect.php");
require("../classes/inputSanitizer.php");
?>

<?php

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR);

// if (isset($_COOKIE["username"])) { // checks if there are cookies available
//     header("Location: dashboard.php"); // if true, login
//     exit();
// }

$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") { // most reliable way when checking for a submitted form or request method

    $isValid = new inputSanitizer();

    $username = $isValid->sanitizeText($_POST["username"]); // FILTERING INPUTS TO PREVENT CROSS SITE SCRIPTS
    $passcode = $_POST["password"] ?? '';

    if (!empty($username) && !empty($passcode)) { // INPUT VALIDATION

        $statement = $conn->prepare("SELECT * FROM users WHERE username = ?"); // PREPARED SQL STATEMENT TO AVOID SQL INJECTION.

        if (!$statement) {
            die("login.php"); // CHECK IF THE PREPARATION WAS SUCCESSFUL and if not, display the error.
        }

        $statement->bind_param("s", $username); // "s" = string, "$sername" = our input
        $statement->execute(); // EXECUTE STATEMENT WITH THE BINDED PARAMETERS/VALUES ABOVE

        $result = $statement->get_result(); // you can get the result of your sql queries.

        if ($result->num_rows > 0) { // checks if the result returned any rows.
            $row = $result->fetch_assoc();
            $hashedPasscode = $row["passcode"];

            if (password_verify($passcode, $hashedPasscode)) {
                // setcookie("username", $row["username"], time() + 3600, "/", "", true, true);
                if ($row["userType"] === "admin") {
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["userType"] = $row["userType"];
                    $_SESSION["userID"] = $row["userID"];
                    header("Location: adminDashboard.php");
                    exit();
                } else {
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["userType"] = $row["userType"];
                    $_SESSION["userID"] = $row["userID"];
                    header("Location: dashboard.php");
                    exit();
                }
            } else {
                $error = "Invalid Username or Password";
            }
        } else {
            $error = "Invalid Username or Password";
        }

        $statement->close();
    } else {
        $error = "Please Fill in all the Fields";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <main>
        <div class="container">
            <h1>LOGIN</h1>
            <br>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post"> <!--$_SERVER["PHP_SELF"] is used to get the current file name-->
                <div class="inputs">

                    <div>
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required>
                    </div>

                    <div>
                        <label for="password">Password:</label> <br>
                        <input type="password" name="password" id="password" required>
                    </div>

                </div>
                <p style="color: red; font-size:small;"><?php echo $error ?? ''; ?></p>
                <a href="registration.php">Don't have an account? Register here.</a>
                <input type="submit" value="LOG IN" name="submit" id="login_btn"> <br>
            </form>
        </div>
    </main>

</body>

</html>