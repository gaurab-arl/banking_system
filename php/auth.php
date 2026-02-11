<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "banking_system";

// Connect to server
$conn = mysqli_connect($servername, $username, $password);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $database";
if (!mysqli_query($conn, $sql)) {
    die("Error creating database: " . mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $database);

// Create users table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!mysqli_query($conn, $sql)) {
    die("Error creating table: " . mysqli_error($conn));
}

// Ensure default admin user exists (void@gmail.com / 123)
$admin_email = "void@gmail.com";
$admin_pass = "123";
$check_admin = mysqli_query($conn, "SELECT id FROM users WHERE email='$admin_email'");
if (mysqli_num_rows($check_admin) == 0) {
    // Attempt to insert with ID 1 for system consistency
    $insert_admin = "INSERT INTO users (id, username, email, phone, password) 
                     VALUES (1, 'Admin', '$admin_email', '0000000000', '$admin_pass')";
    mysqli_query($conn, $insert_admin);
}

// Now $conn is ready to use
// Check POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /* ----------- SIGNUP FORM ----------- */
    if (isset($_POST["signup"])) {
        $name = $_POST["name"];
        $sign_email = $_POST["sign-email"];
        $number = $_POST["number"];
        $login_password = $_POST["login_password"];
        $confirm_password = $_POST["con-pass"];

        if ($login_password == $confirm_password) {
            $sign_email = mysqli_real_escape_string($conn, $sign_email);
            $check_sql = "SELECT * FROM users WHERE email='$sign_email'";
            $check_result = mysqli_query($conn, $check_sql);

            if (mysqli_num_rows($check_result) > 0) {
                echo "<script>alert('Email already registered!');</script>";
            } else {
                $name = mysqli_real_escape_string($conn, $name);
                $number = mysqli_real_escape_string($conn, $number);
                $login_password = mysqli_real_escape_string($conn, $login_password);

                $sql = "INSERT INTO users (username, phone, email, password) 
                        VALUES ('$name', '$number', '$sign_email', '$login_password')";

                if (mysqli_query($conn, $sql)) {
                    echo "<script>window.location.href = '../pages/login.html';</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
                }
            }
        } else {
            echo "<script>alert('Passwords do not match!');</script>";
        }
    }

    /* ----------- LOGIN FORM ----------- */
    if (isset($_POST["login"])) {
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = mysqli_real_escape_string($conn, $_POST["password"]);

        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            echo "<script>alert('Please try logging in again.');</script>";
        } else {
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['email'] = $email;
                echo "<script>window.location.href = 'dashboard.php';</script>";
            } else {
                echo "<script>alert('Invalid email or password!');</script>";
            }
        }
    }
}
?>