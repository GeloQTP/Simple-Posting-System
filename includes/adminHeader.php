<?php
session_start(); // needed at the top everytime we are we are going to access session variables

if (!isset($_SESSION["username"]) || $_SESSION["userType"] !== "admin") {
    header("Location: ../public/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<style>
    header {
        margin: 0rem 0rem;
    }

    nav {
        padding: 1.5rem;
        background-color: #66B3BA;
    }

    #greeting_text {
        font-size: clamp(1rem, 1.5vw, 20rem);
        font-family: Roboto;
    }

    #logout_btn {
        border: none;
        font-size: 1rem;
        padding: 0.5rem;
        border-radius: 5px;
        background-color: #4e42fd;
        cursor: pointer;
        color: white;
    }
</style>

<body>
    <header>
        <nav style="display: flex; align-items: center; justify-content: space-between;">

            <div id="greeting_text">Welcome, <?= $_SESSION["username"] ?>!</div>

            <ul style="display: flex; list-style-type: none; gap: 20px; align-items: center;">
                <li class="notification"><i class="fa fa-bell" style="font-size:25px"></i></li>
                <li class="messages"><i class="fa fa-envelope" style="font-size:25px"></i></li>
                <li class="settings"><i class="fa fa-gear" style="font-size:30px"></i></li>
                <li style="font-size: 30px;"><i class="fa fa-circle"></i></li>
                <form action="logout.php" method="post">
                    <input type="submit" value="Log out" id="logout_btn">
                </form>
            </ul>

        </nav>
    </header>
</body>

</html>