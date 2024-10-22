<?php require_once('../verify_login.php')?> 

<!DOCTYPE html> 
<html lang="en"> 

<head> 
    <meta charset="UTF-8"> 
    <title>ADMIBOT</title> 
    <link rel="stylesheet" href="../styles/homepage.css"> 
    <link rel="stylesheet" href="../styles/interactions.css"> 
    <link rel="stylesheet" href="../styles/image_display.css"> 
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.3.0/css/all.css"> 
    <script src="../scripts/redirect.js"></script> 
    <script src="../scripts/program_img_upload.js"></script> 
    <!-- Add jQuery for AJAX --> 
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head> 

<body class="home-page"> 
    <div class="container"> 
        <div class="side-panel" id="sidePanel"> 
            <div class="home-btn-group"> 
                <?php include_once('../scripts/side_panel.php'); ?> 
            </div> 
        </div> 
        <div class="content"> 
            <div class="top-header"> 
                <div class="page-title"> 
                    <p class="page-name">Images Uploaded</p> 
                </div> 
                <div class="user-profile"> 
                    <?php include_once('../admin_account.php'); ?> 
                    <a href="../logout.php" class="logout-link"> 
                        <i class="fas fa-sign-out-alt"></i> <!-- Font Awesome logout icon --> 
                    </a> 
                </div> 
            </div> 
            <div class="main-menu"> 
                <div class="main-panel"> 
                    <div class="upload-container"> 
                    <!-- Category Dropdown Start --> 
                        <div class="category-selector"> 
                            <label for="category">Select Category:</label> 
                            <select name="category" id="category" required> 
                                <option value="">-- Select Category --</option> 
                                <?php 
                                // List of columns (these remain with underscores in values) 
                                $columns = [ 
                                    'Office_Schedule', 
                                    'SOE_Faculty', 'SOAR_Faculty', 'SOCIT_Faculty', 'SOM_Faculty', 
                                    'SOMA_Faculty', 'SHS_Faculty', 'GS_Faculty', 'Programs_Offered',  
                                    'Other_Information',  
                                    'Accreditations_and_Certifications', 'Tuition_Fees',  
                                    'School_Calendar', 'School_Organizations', 'Floor_Maps' 
                                ]; 

                                // Loop to create dropdown options 
                                foreach ($columns as $column) { 
                                    // Replace underscores with spaces for display 
                                    $displayName = str_replace('_', ' ', $column); 
                                    echo "<option value='$column'>$displayName</option>"; 
                                } 
                                ?> 
                            </select> 
                        </div> 
                    </div> 
                    <!-- Category Dropdown End --> 
                    
                    <!-- Container for Image Display Start -->
                    <div class="image-container"> 
                        <div id="imagesDisplay" class="image-gallery-container"></div> 
                    </div> 
                    <!-- Container for Image Display End -->

                </div> 
            </div> 
        </div> 
    </div> 

        <script>
        $(document).ready(function() {
            $('#category').change(function() {
                var selectedColumn = $(this).val();

                if (selectedColumn) {
                    $.ajax({
                        url: 'fetch_images.php', // Backend script to fetch images
                        type: 'POST',
                        data: { column: selectedColumn },
                        success: function(response) {
                            $('#imagesDisplay').html(response); // Display the images here

                            // Attach event handler for delete buttons
                            $('.delete-image').click(function() {
                                var column = $(this).data('column');
                                var file = $(this).data('file');

                                if (confirm('Are you sure you want to delete this image?')) {
                                    $.ajax({
                                        url: 'delete_image.php',
                                        type: 'POST',
                                        data: { column: column, file: file },
                                        success: function(deleteResponse) {
                                            alert(deleteResponse); // Show response (Image deleted or error)

                                            // Reload the image gallery
                                            $('#category').trigger('change');
                                        }
                                    });
                                }
                            });
                        }
                    });
                } else {
                    $('#imagesDisplay').html(''); // Clear images if no column is selected
                }
            });
        });
    </script>

</body> 

</html>
