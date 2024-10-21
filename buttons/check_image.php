<?php
$image_directory = '../RamiAPI/Images/'; // Path to the image directory

if (isset($_POST['image_name'])) {
    $image_name = basename($_POST['image_name']); // Get the button name (image name)
    $file_path = $image_directory . $image_name . '.png'; // Full path to the image file

    // Check if the image already exists
    if (file_exists($file_path)) {
        echo 'exists'; // File exists
    } else {
        echo 'not_exists'; // File doesn't exist
    }
} else {
    echo 'Invalid request. Image name missing.';
}
?>
