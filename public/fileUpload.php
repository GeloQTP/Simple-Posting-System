<?php

session_start();
require('../includes/db_connect.php');

mysqli_report(MYSQLI_REPORT_STRICT | MYSQLI_REPORT_ERROR); // catch any mysqli (database) errors and turn it into exceptions. 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // checks if the user submitted the form with post method
    die('Invalid request');
}

$post_caption = $_POST['upload_caption'];

if (!isset($_FILES['file_upload']) || $_FILES['file_upload']['error'] !== UPLOAD_ERR_OK) { // checks if $_FILES["you_input_name"] is not empty nor have errors
    die('Please upload a valid file');
}

$targetDir = '../uploads/'; // target path

$maxSize = 5 * 1024 * 1024; // size limit 5mb
if ($_FILES['file_upload']['size'] > $maxSize) { // checks the file size if it's greater than 5,000,000kb (5MB).
    die('File is too large');
}

// file type validation
$allowedTypes = ['jpeg', 'png', 'pdf', 'jpg']; // array of valid or acceptable file types.
$filename = $_FILES['file_upload']['name']; // only gets the base name (image.png).
$extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // pathinfo gets the file extension from the filename and converts it to lowercases.

if (!in_array($extension, $allowedTypes)) { // checks if the extension taken from the pathinfo function is in the array, if not, die.
    die('Invalid file type');
}

// create a Unique filename
$filename = bin2hex(random_bytes(16)) . '.' . $extension; // variable that holds the new base name (file name) (3scxouwhrg5svo.png or whatever)
$targetPath = $targetDir . $filename; // merge the target folder or directory with the new file name (in this case - uploads/3scxouwhrg5svo.png)

if (!move_uploaded_file($_FILES['file_upload']['tmp_name'], $targetPath)) { // does not only checks if the files was moved, but it also does the moving itself (what this does is transfer the uploaded file from the php temporary folder to the new $target path, to it's new name, the original name does not matter at this point)
    die('Upload failed');
}

$stmt = $conn->prepare(
    'INSERT INTO file_uploads (userID, fileName, filePath, caption) VALUES (?, ?, ?, ?)' // uploads the file to the database.
);
$stmt->bind_param(
    'isss',
    $_SESSION['userID'],
    $_FILES['file_upload']['name'],
    $targetPath,
    $post_caption
);
$stmt->execute();
$stmt->close();

header('Location: adminDashboard.php');
exit;
