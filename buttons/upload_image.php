<?php
require_once('../scripts/user_logs.php');

$image_directory = '../RamiAPI/Images/'; // Path to the folder where images are stored

if (isset($_FILES['file']) && isset($_POST['image_name'])) {
    $image_name = basename($_POST['image_name']); // Get the entry name (button name) and sanitize it
    $file = $_FILES['file'];
    $overwrite = isset($_POST['overwrite']) && $_POST['overwrite'] === 'true'; // Check if overwrite is true

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo "Error uploading file.";
        exit;
    }

    $new_filename = $image_directory . $image_name . '.png'; // Save as PNG

    // Check if file already exists
    if (file_exists($new_filename)) {
        if ($overwrite) {
            // Overwrite the file if confirmed by the user
            unlink($new_filename); // Delete the old file
            add_user_log($_SESSION['user_id'], "Updated image file '$image_name.png'");
        } else {
            echo "File already exists and overwrite was not confirmed.";
            exit;
        }
    }

    // Move the uploaded file to the target directory with the new name
    if (move_uploaded_file($file['tmp_name'], $new_filename)) {
        echo "File uploaded successfully!";
        add_user_log($_SESSION['user_id'], "Uploaded image file '$image_name.png'");

    } else {
        echo "Error moving the uploaded file.";
    }
} else {
    echo "Invalid request. File or image name missing.";
}
?>
