<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../database_connect.php');
require_once('../scripts/user_logs.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['emailInput']) && isset($_POST['usernameInput']) && isset($_POST['passwordInput']) && isset($_POST['roleInput']) && isset($_POST['statusInput'])) {
        $email = $_POST['emailInput'];
        $username = $_POST['usernameInput'];
        $password = password_hash($_POST['passwordInput'], PASSWORD_DEFAULT);
        $role = $_POST['roleInput'];
        $user_status = $_POST['statusInput']; // Assuming 1 for enabled, 0 for disabled

        // Check if the email already exists
        $checkEmailSQL = "SELECT COUNT(*) AS count FROM admin_accounts WHERE email = ?";
        $checkStmt = $con->prepare($checkEmailSQL);
        
        if ($checkStmt) {
            $checkStmt->bind_param("s", $email);
            $checkStmt->execute();
            $result = $checkStmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['count'] > 0) {
                // Email already exists
                echo "Error: A user with this email already exists.";
                echo '<br><button onclick="goBack()">Go Back</button>';
                echo '<script>function goBack() { window.history.back(); }</script>';
                $checkStmt->close();
            } else {
                // Proceed with adding the user if the email is unique
                $sql = "INSERT INTO admin_accounts (email, username, password, role, user_status) VALUES (?, ?, ?, ?, ?)";
                $stmt = $con->prepare($sql);
                
                if ($stmt) {
                    $stmt->bind_param("ssssi", $email, $username, $password, $role, $user_status);

                    if ($stmt->execute()) {
                        echo "User added successfully.";
                        echo '<br><button onclick="goBack()">Okay</button>';
                        echo '<script>function goBack() { window.history.back(); }</script>';
                        add_user_log($_SESSION['user_id'], "Added user '" . $username . "'" . " with role '" . $role . "'");  
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Error preparing statement: " . $con->error;
                }
            }
        } else {
            echo "Error preparing email check statement: " . $con->error;
        }
    } else {
        echo "Error: Missing required fields.";
    }

    $con->close();
}
?>
