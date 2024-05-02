<?php
session_start();

if (isset($_SESSION["username"])) {
    $hostname = 'mysql.eecs.ku.edu';
    $username = '447s24_m401c456';
    $password = 'ohzie7Pu';
    $database = '447s24_m401c456';

    $conn = new mysqli($hostname, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["join_line"])) {
        $attractionID = $_POST["attractionID"];
        $userID = $_SESSION["userID"];
        $partyID = $_POST["partyID"];
        
        // Verify if the PartyID exists and is associated with the UserID
        $verifyPartyQuery = "SELECT * FROM P_Membership WHERE PartyID = '$partyID' AND UserID = $userID";
        $verifyPartyResult = $conn->query($verifyPartyQuery);
        
        if ($verifyPartyResult && $verifyPartyResult->num_rows > 0) {
            // Insert into P_InLine table
            $insertLineQuery = "INSERT INTO P_InLine (PartyID, AttractionID, ReadyTime) 
                                VALUES ('$partyID', '$attractionID', NOW())";
            
            if ($conn->query($insertLineQuery) === TRUE) {
                // Alert the user and refresh the page
                echo '<script>alert("Successfully joined the line!"); window.location.href = "./attraction.php";</script>';
                exit;
            } else {
                echo "Error: " . $insertLineQuery . "<br>" . $conn->error;
            }
        } else {
            echo "Error: Invalid PartyID or not associated with the user.";
        }
    }

    $query = "SELECT A.AttractionID, A.AttractionName, I.ReadyTime 
              FROM P_Attraction A 
              LEFT JOIN P_InLine I ON A.AttractionID = I.AttractionID";

    $result = $conn->query($query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Attractions</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp');
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 30px;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1, h3 {
            color: #ff4500;
            text-align: center;
            font-size: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            text-align: center;
        }

        .btn-primary {
            background-color: #ff4500;
            border-color: #ff4500;
            padding: 12px 24px;
            font-size: 16px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
            display: block;
            margin: 20px auto;
        }

        .btn-primary:hover {
            background-color: #e04107;
            border-color: #e04107;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Explore Attractions</h1>
    <?php
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo '<h3>' . $row["AttractionName"] . '</h3>';
            echo '<p>Ready Time: ' . $row["ReadyTime"] . '</p>';
            echo '<form method="post">';
            echo '<input type="hidden" name="attractionID" value="' . $row["AttractionID"] . '">';
            echo '<label for="partyID">Enter Party ID:</label>';
            echo '<input type="text" id="partyID" name="partyID" required>';
            echo '<button type="submit" name="join_line" class="btn btn-primary">Join in Line</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No attractions found.</p>';
    }
    ?>

    <a href="./index.php" class="btn btn-primary">Back</a>
</div>

</body>
</html>

