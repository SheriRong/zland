<?php
$username = $email = $password = $firstname = $lastname = '';
$username_err = $email_err = $password_err = $firstname_err = $lastname_err = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];

    if (empty($username)) {
        $username_err = "Please enter a username.";
    } elseif (!ctype_alnum(str_replace(array("@", "-", "_"), "", $username))) {
        $username_err = "Username can only contain letters, numbers, and symbols like '@', '_', or '-'.";
    }

    if (empty($email)) {
        $email_err = "Please enter an email address.";
    }

    if (empty($password)) {
        $password_err = "Please enter a password.";
    } elseif (strlen($password) < 1) {
        $password_err = "Please enter a password.";
    }

    if (empty($firstname)) {
        $firstname_err = "Please enter your first name.";
    }

    if (empty($lastname)) {
        $lastname_err = "Please enter your last name.";
    } elseif (strlen($lastname) < 1) {
        $lastname_err = "Please enter a last name.";
    }

    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($firstname_err) && empty($lastname_err)) {
        $hostname = 'mysql.eecs.ku.edu';
        $db_username = '447s24_m401c456';
        $db_password = 'ohzie7Pu';
        $database = '447s24_m401c456';

        // Create connection
        $conn = new mysqli($hostname, $db_username, $db_password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and bind SQL statement
        $sql = "INSERT INTO P_User (Username, Password, Email, FirstName, LastName) VALUES ('$username', '$password', '$email', '$firstname', '$lastname')";

        if ($conn->query($sql) === TRUE) {
            echo '<script>alert("Registration completed successfully. Login to continue.");window.location.href = "./index.html";</script>';
            exit;
        } else {
            echo "<script>alert('Oops! Something went wrong. Please try again later.');</script>";
            
        }

        
}}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(240, 240, 240, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .signup-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            width: 300px;
            max-width: 80%;
        }

        h1 {
            color: #ff4500;
            font-size: 28px;
            margin-bottom: 30px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #e04107;
        }

        .signup-link {
            margin-top: 20px;
            font-size: 14px;
        }

        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="overlay">
    <div class="signup-container">
        <h1>Sign up</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <input type="text" name="username" placeholder="Username" required>
                <span class="error"><?php echo $username_err; ?></span>
            </div>
            <div class="mb-3">
                <input type="text" name="email" placeholder="Email Address" required>
                <span class="error"><?php echo $email_err; ?></span>
            </div>
            <div class="mb-3">
                <input type="password" name="password" placeholder="Password" required>
                <span class="error"><?php echo $password_err; ?></span>
            </div>
            <div class="mb-3">
                <input type="text" name="firstname" placeholder="First Name" required>
                <span class="error"><?php echo $firstname_err; ?></span>
            </div>
            <div class="mb-3">
                <input type="text" name="lastname" placeholder="Last Name" required>
                <span class="error"><?php echo $lastname_err; ?></span>
            </div>
            <button type="submit">Sign Up</button>
            <p class="signup-link">Already have an account? <a href="./index.html">Log In</a></p>
        </form>
    </div>
</div>
</body>
</html>
