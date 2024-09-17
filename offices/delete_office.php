<?php
require_once('../database_connect.php');
require_once('../scripts/user_logs.php');

// Ensure the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['office_id'])) {

    $office_id = $_POST['office_id'];

    // Query to get the image URL from the database
    $select_query = "SELECT img_url FROM offices WHERE office_id = ?";
    $stmt = mysqli_prepare($con, $select_query);
    mysqli_stmt_bind_param($stmt, 'i', $office_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $img_url);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Delete the record from the database
    $delete_query = "DELETE FROM offices WHERE office_id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, 'i', $office_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        // Check if the file exists and delete it
        if (file_exists($img_url)) {
            unlink($img_url);
        }

        echo 'Image has been deleted successfully.';
        add_user_log($_SESSION['user_id'], "Deleted office image with ID '" . $office_id . "'");
    } else {
        echo "Error deleting image: " . mysqli_error($con);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($con);
} else {
    echo "Invalid request.";
}
?>
