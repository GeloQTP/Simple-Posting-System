<?php
require("../includes/db_connect.php");
require("../classes/inputSanitizer.php");
?>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $isValid = new inputSanitizer();

    $username = $isValid->sanitizeUsername($_POST["username"]) ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($username) || empty($password)) { // checks if out input fields are empty.
        echo "Please Input your Credentials!";
    } else {
        $hashedPassword = $isValid->hashPassword($password); // hash/encrypts the user password.

        // $statement = "INSERT INTO users (username, passcode) VALUES (?,?)"; (A)

        $statement = $conn->prepare("INSERT INTO users (username, passcode) VALUES (?,?)");
        // sqli object function 'prepare' = It separates the SQL query structure from the data, ensuring that user input cannot alter the query logic.
        //

        $statement->bind_param("ss", $username, $hashedPassword); // A method won't work because this method is object oriented and much safer
        $statement->execute();
        $statement->close();
        header("Location: login.php");
        exit();
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
            <h1>REGISTER</h1>
            <br>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <div class="inputs">

                    <div>
                        <label for="username">Username</label> <br>
                        <input type="text" name="username" id="username" required> <br>
                    </div>

                    <div>
                        <label for="password">Password: </label> <br>
                        <input type="password" name="password" id="password" required> <br>
                    </div>

                </div>
                <a href="login.php">Already have an account? Log in here.</a>
                <input type="submit" value="Register" name="register" id="register_btn">
            </form>
        </div>
    </main>

</body>

</html>