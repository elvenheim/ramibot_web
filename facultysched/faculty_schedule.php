<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ADMIBOT</title>
    <link rel="stylesheet" href="../styles/homepage.css">
    <link rel="stylesheet" href="../styles/interactions.css">
    <link rel="stylesheet" href="../styles/faculty_table.css">
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet'>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.3.0/css/all.css">
    <script src="../scripts/redirect.js"></script>
</head>

<body class="home-page">
    <div class="container">
        <div class="side-panel" id="sidePanel">
            <div class="home-btn-group">
                <div class="main-btn btn faculty-schedule" onclick="redirectPage('../facultysched/faculty_schedule.php')">
                    <div class="main-btn-box"></div>
                    <p>Faculty Schedule</p>
                </div>
                <div class="btn programs-offered" onclick="redirectPage('../programs/programs_offered.php')">
                    <p>Programs Offered</p>
                </div>
                <div class="btn floors" onclick="redirectPage('../offices/office_hours.php')">
                    <p>Floors</p>
                </div>
                <div class="btn office-hours" onclick="redirectPage('../offices/office_hours.php')">
                    <p>Office Hours</p>
                </div>
                <div class="btn announcements" onclick="redirectPage('../announcements/about.php')">
                    <p>About APC</p>
                </div>
                <div class="btn status" onclick="redirectPage('../status/rami_status.php')">
                    <p>Status</p>
                </div>
                <div class="btn calendar" onclick="redirectPage('../calendars/calendars.php')">
                    <p>Calendar</p>
                </div>
                <div class=" btn tuition" onclick="redirectPage('../tuition/tuition.php')">
                    <p>Tuition</p>
                </div>
                <div class="btn accreditations" onclick="redirectPage('../accreditations/accreditations.php')">
                    <p>Accreditations</p>
                </div>
            </div>
        </div>
        <div class="content">
            <div class="top-header">
                <div class="page-title">
                    <p class="page-name">FACULTY SCHEDULE</p>
                </div>
                <div class="user-profile">
                    <?php include_once('../admin_account.php'); ?>
                    <img class="profile-picture" src="" alt="Image of Admin" width="70px" height="70px">
                </div>
            </div>
            <div class="main-menu">
                <div class="main-panel">
                    <form action="faculty_upload.php" method="post" enctype="multipart/form-data" onsubmit="return showFileName()">
                        <div class="upload-container">
                            <div class="upload-button">
                                <label for="fileInput" class="upload-title">
                                    Upload <i class="fas fa-upload"></i>
                                </label>
                                <input type="file" name="fileInput" id="fileInput" accept=".jpg, .jpeg, .png" style="position: absolute; opacity: 0;" onchange="displayFileName(this)">
                            </div>
                            <input type="text" name="faculty_name" id="faculty_name" placeholder="Full Name" required>
                            <input type="text" name="school" id="school" placeholder="Department" required>
                            <input type="submit" value="Submit">
                        </div>
                    </form>
                    <div class="scheds-imgs">
                        <?php include '../facultysched/faculty_images.php' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>