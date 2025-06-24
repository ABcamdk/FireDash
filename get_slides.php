<?php
header('Content-Type: application/json; charset=utf-8');

//changes this db_connection
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(array('error' => "Forbindelse fejlede: " . $conn->connect_error)));
}

$conn->set_charset("utf8");

// Hent kun slides, der ikke er skjulte
$sql = "SELECT id, type, billede_url, billede_filnavn, billede_overskrift, overskrift, tekst, visningstid, fødselsdag_navne, pdf_url FROM slides WHERE skjult = 0 ORDER BY rækkefølge ASC";
$result = $conn->query($sql);

$slides = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $slides[] = $row;
    }
}

$conn->close();

echo json_encode($slides, JSON_UNESCAPED_UNICODE);
?>
