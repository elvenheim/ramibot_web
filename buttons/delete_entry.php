<?php
require_once('../database_connect.php');
require_once('../scripts/user_logs.php');

if (isset($_POST['column']) && isset($_POST['value'])) {
    $column = $_POST['column'];
    $value = $_POST['value'];

    // Prepare the SQL query to delete the database entry
    $sql = "DELETE FROM button_list WHERE $column = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt === false) {
        die("Error preparing the query: " . mysqli_error($con));
    }

    // Bind the value and execute the statement
    mysqli_stmt_bind_param($stmt, 's', $value);

    if (mysqli_stmt_execute($stmt)) {
        // After deleting the database entry, try to delete the image
        $img_url = "../RamiAPI/Images/$value.png"; // Assuming .png extension for images

        if (file_exists($img_url)) {
            if (unlink($img_url)) { // Delete the image file
                echo "Entry and image deleted successfully.";
                // Add user log for both database entry and image deletion
                add_user_log($_SESSION['user_id'], "Deleted button '$value' from column '$column' and image '$value.png'");
            } else {
                echo "Entry deleted, but failed to delete the image.";
                add_user_log($_SESSION['user_id'], "Deleted button '$value' from column '$column', but failed to delete image '$value.png'");
            }
        } else {
            echo "Entry deleted, but no corresponding image found.";
            add_user_log($_SESSION['user_id'], "Deleted button '$value' from column '$column', but image '$value.png' was not found.");
        }
    } else {
        echo "Error deleting entry: " . mysqli_error($con);
    }
} else {
    echo "Invalid request.";
}
?>
