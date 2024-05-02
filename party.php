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

    $userID = $_SESSION["userID"];

    $partysql = "SELECT P_Party.PartyID, P_Party.PartyName FROM P_Party 
                   INNER JOIN P_Membership ON P_Party.PartyID = P_Membership.PartyID 
                   WHERE P_Membership.UserID = '$userID'";
    $partyResult = $conn->query($partysql);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Parties</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
      body {
          font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
          background-image: url('https://ilimoww.com/wp-content/uploads/2022/12/GetPaidStock.com_-6399998ecee15.webp');
          background-size: cover;
          background-position: center;
          margin: 0;
          padding: 0;
          height: 100vh;
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
      }

      p {
          font-size: 18px;
          line-height: 1.6;
          text-align: center;
      }

      .nav-link {
          font-size: 18px;
          color: #007bff;
          transition: color 0.3s ease;
      }

      .nav-link:hover {
          color: #0056b3;
      }
  </style>
</head>
<body>
<div class="container">
    <h1>My Parties</h1>
    <?php
    if (isset($partyResult) && $partyResult->num_rows > 0) {
        while ($partyRow = $partyResult->fetch_assoc()) {
            echo '<div>';
            $partyID = $partyRow['PartyID'];
            $partyName = $partyRow['PartyName'];
            echo "<p>Party ID: $partyID </p>";
            echo "<p> Party Name: $partyName</p>";
            echo '<form method="post">';
            echo '<input type="hidden" name="partyID" value="' . $partyID . '">';
            echo '<button type="submit" name="leave_party" class="btn btn-primary">Leave the Party</button>';
            echo '</form>';
            echo '</div>';
        }
    } else {
        echo '<p>No Party Found.</p>';
    }
    ?>
    <a href="createparty.php" class="nav-link">Create a Party</a>
    <a href="joinparty.php" class="nav-link">Join a Party</a>
    <a href="index.php" class="btn btn-primary">Back</a>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["leave_party"])) {
    $partyID = $_POST["partyID"];
    $deleteQuery = "DELETE FROM P_Membership WHERE PartyID = $partyID";
    if ($conn->query($deleteQuery) === TRUE) {
        echo '<script>alert("You have left the party.");</script>';
        echo '<script>window.location.href = "./party.php";</script>';
        exit;
    } else {
        echo '<script>alert("Failed to leave the party. Pleascde try again.");</script>';
    }
}

$conn->close();
?>
</body>
</html>
