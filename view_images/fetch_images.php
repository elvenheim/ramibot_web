<?php
require_once('../verify_login.php');
require_once('../database_connect.php'); // Ensure this file contains the DB connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['column'])) {
    $column = $_POST['column'];

    // Sanitize the column input to prevent SQL injection
    $allowed_columns = [
        'Office_Schedule', 
        'SOE_Faculty', 'SOAR_Faculty', 'SOCIT_Faculty', 'SOM_Faculty',
        'SOMA_Faculty', 'SHS_Faculty', 'GS_Faculty', 'Programs_Offered', 
        'Other_Information', 
        'Accreditations_and_Certifications', 'Tuition_Fees', 
        'School_Calendar', 'School_Organizations', 'Floor_Maps'
    ];

    if (!in_array($column, $allowed_columns)) {
        echo 'Invalid column selected.';
        exit;
    }

    // Query the database to get the values from the selected column
    $query = "SELECT $column FROM button_list WHERE $column IS NOT NULL AND $column <> ''";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        echo "<div class='image-gallery'>"; // Start of image gallery

        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            $value = $row[$column];

            // Construct the image filename (Assuming the image files follow a pattern based on the column values)
            $img_url = "../RamiAPI/Images/$value.png"; // Example: image files stored in the 'uploads' folder with .jpg extension

            // Check if the image file exists
            if (file_exists($img_url)) {
                echo "<div class='image-item'>";
                echo "<p class='file-name'>$value.png</p>"; // Display the file name above the image
                echo "<img src='$img_url' alt='$value'>";
                echo "</div>";
            } else {
                echo "<div class='image-item'>";
                echo "<p class='file-name'>$value.png</p>";
                echo "<p>No image found for $value</p>";
                echo "</div>";
            }
        }

        echo "</div>"; // End of image gallery
    } else {
        echo 'No data found in the selected column.';
    }
}
?>


