<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "ctf";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$player = $_POST['username'];
$sql = "SELECT username, password FROM users WHERE username = '$username'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo" - Name: " . $row["username"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>