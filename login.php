<?php
session_start();

$hostname = 'mysql.eecs.ku.edu';
$username = '447s24_m401c456';
$password = 'ohzie7Pu';
$database = '447s24_m401c456';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_username = $_POST['login_username'];
    $login_password = $_POST['login_password'];

    $login_username = $conn->real_escape_string($login_username);
    $login_password = $conn->real_escape_string($login_password);

    $query = "SELECT * FROM P_User WHERE Username='$login_username' AND Password='$login_password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $login_username;
      while ($row = $result->fetch_assoc()){
        $_SESSION["userID"] = $row["UserID"];}
        header("Location: index.php");
        exit;
    } else {
        // Login failed, redirect to index.html with error alert
        $_SESSION["login_error"] = "Invalid username or password. Please try again.";
        header("Location: index.html");
        exit;
    }
}

$conn->close();
?>

