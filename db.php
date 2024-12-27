<?php
$servername = "localhost";
$username = "root";
$password = "Atom#12345";
$dbname = "todo_app";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>

