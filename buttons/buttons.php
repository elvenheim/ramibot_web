<?php require_once('../verify_login.php')?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADMIBOT</title>
    <link rel="stylesheet" href="../styles/homepage.css">
    <link rel="stylesheet" href="../styles/interactions.css">
    <link rel="stylesheet" href="../styles/buttons.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.3.0/css/all.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../scripts/redirect.js"></script>
</head>
<body class="home-page">
    <div class="container">
        <div class="side-panel" id="sidePanel">
            <div class="home-btn-group">
                <?php include_once '../scripts/side_panel.php'; ?>
            </div>
        </div>
        <div class="content">
            <div class="top-header">
                <div class="page-title">
                    <p class="page-name">BUTTON LISTS</p>
                </div>
                <div class="user-profile">
                    <?php include_once '../admin_account.php'; ?>
                    <a href="../logout.php" class="logout-link">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            <div class="main-menu">
                <div class="main-panel">
                    <div class="buttons-table-container">
                        <div class="edit-button-form">
                            <form method="POST" id="categoryForm" action="">
                                <div class="form-header">
                                    <div class="category-selector">
                                        <label for="category">Select Category:</label>
                                        <select name="category" id="category" required>
                                            <option value="">-- Select Category --</option>
                                            <?php
                                            // List of categories
                                            $columns = ['Office_Schedule','SOE_Faculty', 'SOAR_Faculty', 'SOCIT_Faculty', 'SOM_Faculty', 'SOMA_Faculty', 'SHS_Faculty', 'GS_Faculty', 'Programs_Offered', 'Other_Information', 'Accreditations_and_Certifications', 'Tuition_Fees', 'School_Calendar', 'School_Organizations', 'Floor_Maps'];
                                            
                                            foreach ($columns as $column) {
                                                echo "<option value='$column'>$column</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="button-group">
                                        <button type="button" id="add-entry-button">Add Entry</button>
                                        <button type="button" id="save-all-entries-button">Save All Entries</button>
                                    </div>
                                </div>
                                <h3>Entries</h3>
                                <ul id="entries-list">
                                </ul>
                                <div id="image-preview-modal" class="modal" style="display: none;">
                                    <div class="modal-content">
                                        <span id="close-preview-modal" class="close">&times;</span>
                                        <div class="modal-body">
                                            <!-- Image will be loaded here dynamically -->
                                        </div>
                                    </div>
                                </div>
                                <div id="new-entry-section" style="display: none;">
                                    <h4>Add New Entry</h4>
                                    <div>
                                        <input type="text" id="new-entry" placeholder="Enter new entry value">
                                        <button type="button" id="submit-new-entry">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Handle category change
            $('#category').change(function() {
                var category = $(this).val();
                if (category) {
                    $.ajax({
                        type: 'POST',
                        url: 'fetch_entries.php',
                        data: { category: category },
                        success: function(response) {
                            $('#entries-list').html(response);
                        },
                        error: function(xhr, status, error) {
                            $('#entries-list').html('<p>Error loading data.</p>');
                        }
                    });
                } else {
                    $('#entries-list').html('');
                }
            });

            // Toggle new entry section
            $('#add-entry-button').click(function() {
                $('#new-entry-section').toggle(); 
            });

            // Handle new entry submission
            $('#submit-new-entry').click(function() {
                var newEntry = $('#new-entry').val();
                var category = $('#category').val(); 

                if (newEntry && category) {
                    var newEntryHtml = "<li><span class='entry-value'>" + newEntry + "</span>";
                    newEntryHtml += "<button type='button' class='delete-btn' data-column='" + category + "' data-value='" + newEntry + "'>Delete</button></li>";
                    
                    $('#entries-list').append(newEntryHtml);
                    $('#new-entry').val(''); 
                    $('#new-entry-section').hide();
                } else {
                    alert('Please enter a value for the new entry and select a category.');
                }
            });

            // Handle saving all entries
            $('#save-all-entries-button').click(function() {
                var entries = [];
                $('.entry-value').each(function() {
                    entries.push($(this).text());
                });

                var category = $('#category').val();

                if (category && entries.length > 0) {
                    $.ajax({
                        type: 'POST',
                        url: 'update_entries.php',
                        data: { category: category, entries: JSON.stringify(entries) },
                        success: function(response) {
                            alert(response);
                            $('#category').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            alert('Error saving entries: ' + xhr.responseText);
                        }
                    });
                } else {
                    alert('No entries to save or category not selected.');
                }
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function() {
                var column = $(this).data('column');
                var value = $(this).data('value');

                if (confirm('Are you sure you want to delete this entry?')) {
                    $.ajax({
                        type: 'POST',
                        url: 'delete_entry.php',
                        data: { column: column, value: value },
                        success: function(response) {
                            alert(response);
                            $('#category').trigger('change');
                        },
                        error: function(xhr, status, error) {
                            alert('Error deleting entry.');
                        }
                    });
                }
            });

            // Handle the Upload button click
            $(document).on('click', '.upload-btn', function() {
                var entryValue = $(this).data('value'); // Get the button name (value)
                var column = $(this).data('column');

                // Create a file input element dynamically
                var fileInput = $('<input type="file" accept="image/*">');
                
                // Trigger the file input to open the file selection dialog
                fileInput.trigger('click');

                // Listen for file selection
                fileInput.on('change', function(event) {
                    var file = event.target.files[0]; // Get the selected file
                    
                    if (file) {
                        // Check if an image with the same name already exists
                        $.ajax({
                            url: 'check_image.php', // Server-side script to check if the image exists
                            type: 'POST',
                            data: { image_name: entryValue }, // Send the image name (button name)
                            success: function(response) {
                                if (response === 'exists') {
                                    // If the file exists, ask for confirmation to overwrite
                                    if (confirm('An image with the same name already exists. Do you want to overwrite it?')) {
                                        uploadFile(file, entryValue, column, true); // Overwrite the file
                                    } else {
                                        alert('Upload canceled.');
                                    }
                                } else {
                                    // If the file doesn't exist, proceed to upload
                                    uploadFile(file, entryValue, column, false); // Normal upload
                                }
                            },
                            error: function(xhr, status, error) {
                                alert('Error checking file: ' + xhr.responseText);
                            }
                        });
                    } else {
                        alert('No file selected.');
                    }
                });
            });

            // Function to handle the file upload
            function uploadFile(file, entryValue, column, overwrite) {
                var formData = new FormData();
                formData.append('file', file);
                formData.append('image_name', entryValue);
                formData.append('column', column);
                formData.append('overwrite', overwrite); // Send overwrite flag to the server

                $.ajax({
                    url: 'upload_image.php', // Upload to the server
                    type: 'POST',
                    data: formData,
                    processData: false, // Prevent jQuery from processing the formData
                    contentType: false, // Prevent jQuery from setting content-type header
                    success: function(response) {
                        alert(response); // Display success or error message
                    },
                    error: function(xhr, status, error) {
                        alert('Error uploading file: ' + xhr.responseText);
                    }
                });
            }

            // Handle the Preview button click
            $(document).on('click', '.preview-btn', function() {
                var entryValue = $(this).data('value'); // Get the entry value

                $.ajax({
                    type: 'POST',
                    url: 'preview_image.php',
                    data: { image_name: entryValue },
                    success: function(response) {
                        console.log(response);  // Log the response to see what is returned
                        var result = JSON.parse(response);

                        if (result.status === 'success') {
                            // If image exists, preview the image in a modal
                            var imageUrl = result.image_path;
                            var previewHtml = '<img src="' + imageUrl + '" alt="Preview Image" style="max-width: 100%; height: auto;">';
                            $('#image-preview-modal .modal-body').html(previewHtml);
                            $('#image-preview-modal').show(); // Show the modal
                        } else {
                            // If no image, show the error message
                            alert(result.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error: ', xhr.responseText);  // Log the error for debugging
                        alert('Error checking image: ' + xhr.responseText);
                    }
                });
            });
            // Close modal logic
            $('#close-preview-modal').click(function() {
                $('#image-preview-modal').hide();
            });

        });
    </script>

</body>
</html>
