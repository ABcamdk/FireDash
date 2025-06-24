<!DOCTYPE html>
<html>
<head>
    <title>Editor - Image Upload</title>
</head>
<body>

<h1>Upload Image URL</h1>

<form action="upload.php" method="post">
    <label for="billede_url">Image URL:</label><br>
    <input type="text" id="billede_url" name="billede_url" size="50"><br><br>
    <input type="submit" value="Gem Billede">
</form>

<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $billede_url = $_POST["billede_url"];

    // Validering (vigtigt!)
    if (empty($billede_url)) {
        echo "<p style='color: red;'>Fejl: Billede URL er påkrævet.</p>";
    } elseif (!filter_var($billede_url, FILTER_VALIDATE_URL)) {
        echo "<p style='color: red;'>Fejl: Ugyldig Billede URL.</p>";
    } else {
        // Databaseforbindelse
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "dbname";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Tjek forbindelse
        if ($conn->connect_error) {
            die("Forbindelse fejlede: " . $conn->connect_error);
        }

        // Undgå SQL-injection med prepared statements
        $sql = "INSERT INTO billeder (billede_url) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $billede_url);  // "s" angiver en streng

        if ($stmt->execute() === TRUE) {
            echo "<p style='color: green;'>Billede URL gemt!</p>";
        } else {
            echo "<p style='color: red;'>Fejl: " . $stmt->error . "</p>";
        }

        $stmt->close();
        $conn->close();
    }
}

?>

</body>
</html>
