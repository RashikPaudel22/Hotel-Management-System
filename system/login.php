<?php
session_start();

include("database.php");
require_once("htmlload.php");

// Check if this is an AJAX request
$isAjax = isset($_POST['ajax_login']);

if (isset($_POST['login']) || $isAjax) {

    // Get form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate user (NOTE: You should use prepared statements for security!)
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        // Set session values
        $_SESSION['user'] = [
            "id" => $row['id'],
            "username" => $row['username'],
            "role" => $row['role']
        ];

        // Determine redirect URL based on role
        if ($row['role'] === "admin") {
            $redirectUrl = "/hms/app/admin/dashboard.php";
        } 
        elseif ($row['role'] === "receptionist") {
            $redirectUrl = "/hms/app/reception/dashboard.php";
        } 
        else {
            $redirectUrl = "/hms/app/customer/dashboard.php";
        }

        // If AJAX request, return JSON
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'role' => $row['role'],
                'redirect' => $redirectUrl
            ]);
            exit;
        } else {
            // Regular form submission
            header("Location: " . $redirectUrl);
            exit;
        }

    } else {
        // Login failed
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
            exit;
        } else {
            // Show error + login form again (HTML from htmlload.php)
            loadHeader("Login");
            echo "<p style='color:red;text-align:center'>Invalid username or password</p>";
            login_form(); 
            loadFooter();
        }
    }

} else {
    // If opened normally, show login form
    loadHeader("Login");
    login_form();
    loadFooter();
}
?>