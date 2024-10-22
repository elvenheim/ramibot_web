<?php
require_once('../verify_login.php');
require_once('../scripts/user_logs.php');
// Ensure this file contains the DB connection details
require_once('../database_connect.php'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $file = $_POST['file'];

    // Sanitize inputs if needed
    $img_url = "../RamiAPI/Images/$file.png";

    // Remove the image file from the file system
    if (file_exists($img_url)) {
        if (unlink($img_url)) { // Delete the file
            echo 'Image file deleted successfully.';
            add_user_log($_SESSION['user_id'], "Deleted image file '$file.png'");
        } else {
            echo 'Error deleting the image file.';
        }
    } else {
        echo 'Image file not found.';
    }
}
?>
