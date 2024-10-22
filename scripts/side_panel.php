<?php  
require_once('../database_connect.php');

// Check the logged-in user's role from the session
$userID = $_SESSION['user_id'];

// Query to get the current user's role
$sql = "SELECT role FROM admin_accounts WHERE user_id = ?";
$stmt = mysqli_prepare($con, $sql);
if ($stmt === false) {
    die("Failed to prepare the statement: " . mysqli_error($con));
}
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch the user's role
$userRole = null;
if ($row = mysqli_fetch_assoc($result)) {
    $userRole = $row['role'];
}
mysqli_free_result($result);
mysqli_stmt_close($stmt);

// Get the current page
$currentPage = basename($_SERVER['REQUEST_URI']);

// Function to check if a page is active
function isActivePage($page) {
    global $currentPage;
    return $currentPage === basename($page) ? 'main-btn btn-indicator' : '';
}

// Define allowed buttons for each role
$allButtons = [
    ['path' => '../super_admin/manage_users.php', 'label' => 'Manage Users'],
    ['path' => '../view_images/image_page.php', 'label' => 'Images'],
    ['path' => '../status/rami_status.php', 'label' => 'Status'],
    ['path' => 'http://192.168.80.4:5050', 'label' => 'Ramibot Response'],
    ['path' => '../buttons/buttons.php', 'label' => 'Button Lists'],
];

// Allowed pages per role
$allowedButtons = [
    1 => $allButtons, // Role 1 gets access to all buttons
    2 => array_filter($allButtons, fn($button) => $button['path'] !== '../super_admin/manage_users.php'), // Role 2 restricted from 'Manage Users'
    3 => array_filter($allButtons, fn($button) => !str_contains($button['path'], 'manage_users') && !str_contains($button['path'], 'rami_status') && !str_contains($button['path'], 'buttons')) // Role 3 restrictions
];

// Render buttons based on role
if (isset($allowedButtons[$userRole])) {
    foreach ($allowedButtons[$userRole] as $button) {
        echo '<div class="btn ' . isActivePage($button['path']) . '" onclick="redirectPage(\'' . $button['path'] . '\')">';
        echo '<p>' . $button['label'] . '</p>';
        echo '</div>';
    }
} else {
    echo "Access denied.";
}
?>
