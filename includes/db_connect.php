<?php

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ALL); // needed
// without this, even if our try catch block catches the error the web will still output this error

//PHP does not report mysqli or PDO errors by default because that information is highly sensitive, displaying it to a user is a great way to learn how to inject malicious data.

// MYSQLI_REPORT_ALL tells it to turn on the errors and MYSQLI_REPORT_STRICT tells it to convert those errors into Exceptions. This will give you a full report of the error message, so if you do this in production make sure that you do not display it to the end user.

//Warning: mysqli_connect(): (HY000/2002): No connection could be made because the target machine actively refused it in C:\xampp\htdocs\InputSanitizationAndPasswordHashing\db_connect.php on line 9

$db_host = 'localhost'; // variables we need that contains data from our database.
$db_user = 'root';
$db_pass = '';
$db_name = 'dashboarddb';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
} catch (mysqli_sql_exception) { // catch if there is an error with our mysqli_connect().
    echo '<p style="color:red;">Database Connection Error. Please Try again Later.</p>';
}
