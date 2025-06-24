<?php
header('Content-Type: text/plain; charset=utf-8');

//changes this db_connect
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Fejl ved forbindelse til databasen: " . $conn->connect_error);
}

$conn->set_charset("utf8");

$sql = "SELECT besked FROM konfiguration LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row["besked"];
} else {
    echo "Ingen besked fundet.";
}

$conn->close();
?>
