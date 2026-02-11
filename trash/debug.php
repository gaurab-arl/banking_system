<?php
include("../php/auth.php");

$servername = "localhost";
$username = "root";
$xampp_password = "";
$database = "test";

$conn = mysqli_connect($servername, $username, $xampp_password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    echo "<script>
        alert('Please login first');
        window.location.href = '../login.php';
    </script>";
    exit();
}

$email = $_SESSION['email'];

// Fetch the user_id for the logged-in user
$user_query = "SELECT id FROM users WHERE email = '$email'";
$user_result = mysqli_query($conn, $user_query);

if (!$user_result || mysqli_num_rows($user_result) == 0) {
    die("User not found");
}

$user_row = mysqli_fetch_assoc($user_result);
$user_id = $user_row['id'];

// Fetch transactions for the logged-in user
$sql = "SELECT * FROM transactions WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($email); ?></h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
            <th>From Account</th>
            <th>To Account</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['type']}</td>
                        <td>{$row['amount']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['from_account']}</td>
                        <td>{$row['to_account']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['created_at']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='8'>No transactions found for this account.</td></tr>";
        }
        ?>
    </table>
</body>
</html>
