<?php
// preview_image.php

$image_directory = '../RamiAPI/Images/'; // Path to the folder where images are stored

if (isset($_POST['image_name'])) {
    $image_name = basename($_POST['image_name']); // Sanitize input
    $image_path = $image_directory . $image_name . '.png'; // Assuming images are .jpg format.

    if (file_exists($image_path)) {
        // Image exists, return the path
        echo json_encode(['status' => 'success', 'image_path' => $image_path]);
    } else {
        // Image does not exist
        echo json_encode(['status' => 'error', 'message' => 'No image has been uploaded for this entry.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>