<?php
// Tænd for fejlrapportering for at se eventuelle problemer direkte på siden.
// FJERN DISSE LINJER PÅ EN LIVE SERVER AF SIKKERHEDSGRUNDE!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ob_start(); // Start output buffering

// Databaseforbindelsesoplysninger
$servername = "localhost";
$username = "root"; // Dobbelttjek dette
$password = "root"; // Dobbelttjek dette
$dbname = "main_db"; // Dobbelttjek dette

// Opret forbindelse
$conn = new mysqli($servername, $username, $password, $dbname);

// Tjek forbindelsen
if ($conn->connect_error) {
    // Hvis forbindelsen fejler, stop scriptet og vis fejlmeddelelsen.
    die("Forbindelse til databasen fejlede: " . $conn->connect_error);
}

// Indstil tegnkodning for forbindelsen
$conn->set_charset("utf8");

// --- PHP Logik til håndtering af formularindsendelser og sletning ---

// Håndtering af slide-oprettelse
if (isset($_POST["opret_slide"])) {
    $type = $_POST["type"];
    $billede_url = isset($_POST["billede_url"]) ? $_POST["billede_url"] : "";
    $billede_filnavn = ""; // Initialiser altid, da den kan være tom
    $billede_overskrift = isset($_POST["billede_overskrift"]) ? $_POST["billede_overskrift"] : "";
    $overskrift = isset($_POST["overskrift"]) ? $_POST["overskrift"] : "";
    $tekst = isset($_POST["tekst"]) ? $_POST["tekst"] : "";
    $fødselsdag_navne = isset($_POST["fødselsdag_navne"]) ? $_POST["fødselsdag_navne"] : "";
    $pdf_url = isset($_POST["pdf_url"]) ? $_POST["pdf_url"] : "";
    $visningstid = isset($_POST["visningstid"]) ? intval($_POST["visningstid"]) : 10;

    // Håndtering af billede upload
    if (isset($_FILES["billede_upload"]) && $_FILES["billede_upload"]["error"] == 0) {
        $allowed_exts = array("jpg", "jpeg", "png", "gif");
        $temp = explode(".", $_FILES["billede_upload"]["name"]);
        $ext = strtolower(end($temp));

        if (in_array($ext, $allowed_exts)) {
            $billede_filnavn = uniqid() . "." . $ext;
            $target_path = "uploads/" . $billede_filnavn; // Sørg for at 'uploads' mappen findes og er skrivbar!

            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true); // Opret mappen hvis den ikke eksisterer
            }

            if (move_uploaded_file($_FILES["billede_upload"]["tmp_name"], $target_path)) {
                // Filen er uploadet succesfuldt
            } else {
                echo "<p style='color: red;'>Fejl ved upload af billede til: " . htmlspecialchars($target_path) . " (Check mappetilladelser).</p>";
            }
        } else {
            echo "<p style='color: red;'>Ugyldig filtype. Kun JPG, JPEG, PNG og GIF er tilladt.</p>";
        }
    }

    // Bestem den næste rækkefølge
    $sql_max_order = "SELECT MAX(rækkefølge) AS max_rækkefølge FROM slides";
    $result_max_order = $conn->query($sql_max_order);

    $næste_rækkefølge = 1; // Standardværdi
    if ($result_max_order && $result_max_order->num_rows > 0) {
        $row_max_order = $result_max_order->fetch_assoc();
        if ($row_max_order["max_rækkefølge"] !== null) { // Tjek om resultatet er null (tom tabel)
            $næste_rækkefølge = intval($row_max_order["max_rækkefølge"]) + 1;
        }
    } else {
        echo "<p style='color: orange;'>Advarsel: Kunne ikke hente max rækkefølge. Starter fra 1. (" . $conn->error . ")</p>";
    }

    // Indsæt i databasen
    $sql_insert = "INSERT INTO slides (type, billede_url, billede_filnavn, billede_overskrift, overskrift, tekst, visningstid, rækkefølge, fødselsdag_navne, pdf_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    if ($stmt_insert === false) {
        echo "<p style='color: red;'>Fejl ved forberedelse af slide-oprettelse: " . $conn->error . "</p>";
    } else {
        $stmt_insert->bind_param("ssssssiiss", $type, $billede_url, $billede_filnavn, $billede_overskrift, $overskrift, $tekst, $visningstid, $næste_rækkefølge, $fødselsdag_navne, $pdf_url);

        if ($stmt_insert->execute() === TRUE) {
            // Echo en succesmeddelelse, som ikke nødvendigvis vises pga. redirect
            // echo "<p style='color: green;'>Slide oprettet!</p>";
        } else {
            echo "<p style='color: red;'>Fejl ved oprettelse af slide: " . $stmt_insert->error . "</p>";
        }
        $stmt_insert->close();
    }
    header("Location: editor.php"); // Refresh siden
    exit();
}

// Håndtering af skjul slide
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["slide_id"])) {
    $slide_id = intval($_POST["slide_id"]);
    $skjult = isset($_POST["skjult"]) && $_POST["skjult"] == '1' ? 1 : 0;

    $sql_update_hidden = "UPDATE slides SET skjult = ? WHERE id = ?";
    $stmt_update_hidden = $conn->prepare($sql_update_hidden);

    if ($stmt_update_hidden === false) {
        echo "<p style='color: red;'>Fejl ved forberedelse af skjul/vis slide: " . $conn->error . "</p>";
    } else {
        $stmt_update_hidden->bind_param("ii", $skjult, $slide_id);
        if ($stmt_update_hidden->execute() === TRUE) {
            // echo "<p style='color: green;'>Slide skjult status opdateret!</p>";
        } else {
            echo "<p style='color: red;'>Fejl ved opdatering af skjult status: " . $stmt_update_hidden->error . "</p>";
        }
        $stmt_update_hidden->close();
    }
    header("Location: editor.php"); // Refresh siden
    exit();
}

// Håndtering af besked opdatering
if (isset($_POST["opdater_besked"])) {
    $besked = $_POST["besked"];

    $sql_update_message = "UPDATE konfiguration SET besked = ?";
    $stmt_update_message = $conn->prepare($sql_update_message);

    if ($stmt_update_message === false) {
        echo "<p style='color: red;'>Fejl ved forberedelse af beskedopdatering: " . $conn->error . "</p>";
    } else {
        $stmt_update_message->bind_param("s", $besked);
        if ($stmt_update_message->execute() === TRUE) {
            // echo "<p style='color: green;'>Besked opdateret!</p>";
        } else {
            echo "<p style='color: red;'>Fejl ved opdatering af besked: " . $stmt_update_message->error . "</p>";
        }
        $stmt_update_message->close();
    }
    header("Location: editor.php");
    exit();
}

// Håndtering af slet slide
if (isset($_GET["slet"])) {
    $slide_id = intval($_GET["slet"]);

    $sql_delete = "DELETE FROM slides WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);

    if ($stmt_delete === false) {
        echo "<p style='color: red;'>Fejl ved forberedelse af slet slide: " . $conn->error . "</p>";
    } else {
        $stmt_delete->bind_param("i", $slide_id);
        if ($stmt_delete->execute() === TRUE) {
            // echo "<p style='color: green;'>Slide slettet!</p>";
        } else {
            echo "<p style='color: red;'>Fejl ved sletning af slide: " . $stmt_delete->error . "</p>";
        }
        $stmt_delete->close();
    }
    header("Location: editor.php"); // Refresh siden
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>FireDash - Editor</title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap">
    <style>
        /* Generel Styling */
        body {
            font-family: 'Lato', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            background-image: url("skyblue.jpg"); /* Angiver billedet som baggrund */
            background-size: cover; /* Skalerer billedet til at dække hele baggrunden */
            background-repeat: no-repeat; /* Undgår gentagelse af billedet */
            background-attachment: fixed; /* Fixerer baggrunden, så den ikke scroller */
        }

        h1, h2 {
            color: #333;
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box; /* Sørg for at padding ikke ødelægger bredden */
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        /* Slide Liste Styling */
        #slide_liste {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .slide {
            width: 300px;
            background-color: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .slide strong {
            font-weight: bold;
        }

        .slide a {
            color: #d9534f;
            text-decoration: none;
        }

        .slide a:hover {
            text-decoration: underline;
        }

        /* Tabs Styling */
        .tab-container {
            display: flex;
            margin-bottom: 0;
            border-bottom: 1px solid #ccc;
        }

        .tab-button {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-bottom: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .tab-button:hover {
            background-color: #e2e2e2;
        }

        .tab-button.active {
            background-color: #fff;
            border-bottom: 1px solid #fff;
            color: #333;
            font-weight: bold;
        }

        .tab-content {
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-top: none;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
    </style>
    <script>
       // JavaScript til at vise/skjule felter baseret på slide-type
    function skiftTypeFelter() {
        const typeSelect = document.getElementById("type");
        const billedeFelter = document.getElementById("billede_felter");
        const tekstFelter = document.getElementById("tekst_felter");
        const fødselsdagFelter = document.getElementById("fødselsdag_felter");
        const pdfFelter = document.getElementById("pdf_felter");

        // Skjul alle felter først
        billedeFelter.style.display = "none";
        tekstFelter.style.display = "none";
        fødselsdagFelter.style.display = "none";
        pdfFelter.style.display = "none";

        // Vis kun de relevante felter baseret på valgt type
        if (typeSelect.value === "billede") {
            billedeFelter.style.display = "block";
        } else if (typeSelect.value === "tekst") {
            tekstFelter.style.display = "block";
        } else if (typeSelect.value === "fødselsdag") {
            fødselsdagFelter.style.display = "block";
        } else if (typeSelect.value === "pdf") {
            pdfFelter.style.display = "block";
        }
    }

    // JavaScript til at håndtere tabs
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-button");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Kald funktionen, når siden indlæses for at vise den første tab og initialisere felter
    window.onload = function() {
        skiftTypeFelter(); // Initialiser felter baseret på standardvalg
        document.getElementById("defaultOpen").click(); // Åbn den første tab som standard
    };
    </script>
</head>
<body>

<h1>Infoskærm Editor</h1>

<div class="tab-container">
    <button class="tab-button" onclick="openTab(event, 'createSlide')" id="defaultOpen">Add New Slide</button>
    <button class="tab-button" onclick="openTab(event, 'manageSlides')">Manage Slides and Message</button>
</div>

<div id="createSlide" class="tab-content">
    <form action="editor.php" method="post" enctype="multipart/form-data">
        <h2>Add New Slide</h2>

        <label for="type">Type:</label>
        <select id="type" name="type" onchange="skiftTypeFelter()">
            <option value="billede">Image</option>
            <option value="tekst">Text</option>
            <option value="fødselsdag">Birthday</option>
            <option value="pdf">PDF</option>
        </select><br><br>

        <div id="billede_felter" style="display: none;">
            <label for="billede_url">Image URL (Optional if uploading):</label><br>
            <input type="text" id="billede_url" name="billede_url" size="50"><br>
            <label for="billede_upload">Upload Image (Will overwrite URL):</label><br>
            <input type="file" id="billede_upload" name="billede_upload"><br><br>
            <label for="billede_overskrift">Image Header:</label><br>
            <input type="text" id="billede_overskrift" name="billede_overskrift" size="50"><br><br>
        </div>

        <div id="tekst_felter" style="display: none;">
            <label for="overskrift">Heading:</label><br>
            <input type="text" id="overskrift" name="overskrift" size="50"><br>
            <label for="tekst">Text:</label><br>
            <textarea id="tekst" name="tekst" rows="4" cols="50"></textarea><br><br>
        </div>

        <div id="fødselsdag_felter" style="display: none;">
            <label for="fødselsdag_navne">Names (separated by commas):</label><br>
            <input type="text" id="fødselsdag_navne" name="fødselsdag_navne" size="50"><br><br>
        </div>

          <div id="pdf_felter" style="display: none;">
            <label for="pdf_url">PDF URL:</label><br>
            <input type="text" id="pdf_url" name="pdf_url" size="50"><br><br>
        </div>

        <label for="visningstid">Display time (seconds)::</label>
        <input type="number" id="visningstid" name="visningstid" value="10"><br><br>

        <input type="submit" name="opret_slide" value="Opret Slide">
    </form>
</div>

<div id="manageSlides" class="tab-content">
    <h2>Existing Slides</h2>

    <?php
    // Hent alle slides sorteret efter rækkefølge
    $sql_select_slides = "SELECT * FROM slides ORDER BY rækkefølge ASC";
    $result_select_slides = $conn->query($sql_select_slides);

    if ($result_select_slides) { // Tjek om forespørgslen lykkedes
        if ($result_select_slides->num_rows > 0) {
            echo "<div id='slide_liste'>";
            while ($row = $result_select_slides->fetch_assoc()) {
                echo "<div class='slide'>";
                echo "<strong>Type:</strong> " . htmlspecialchars($row["type"]) . "<br>";
                if ($row["type"] == "billede") {
                    echo "<strong>Billede URL:</strong> " . htmlspecialchars($row["billede_url"]) . "<br>";
                    if (!empty($row["billede_filnavn"])) {
                        echo "<strong>Uploadet Fil:</strong> " . htmlspecialchars($row["billede_filnavn"]) . "<br>";
                    }
                    echo "<strong>Billede Overskrift:</strong> " . htmlspecialchars($row["billede_overskrift"]) . "<br>";
                } elseif ($row["type"] == "tekst") {
                    echo "<strong>Overskrift:</strong> " . htmlspecialchars($row["overskrift"]) . "<br>";
                    echo "<strong>Tekst:</strong> " . htmlspecialchars($row["tekst"]) . "<br>";
                } elseif ($row["type"] == "fødselsdag") {
                    echo "<strong>Fødselsdag Navne:</strong> " . htmlspecialchars($row["fødselsdag_navne"]) . "<br>";
                } elseif ($row["type"] == "pdf") {
                    echo "<strong>PDF URL:</strong> " . htmlspecialchars($row["pdf_url"]) . "<br>";
                }
                echo "<strong>Visningstid:</strong> " . htmlspecialchars($row["visningstid"]) . " sekunder<br>";
                // Skjul slide med checkbox
                echo "<form action='editor.php' method='post' style='display:inline-block; margin-right:10px;'><input type='hidden' name='slide_id' value='" . $row["id"] . "'><label><input type='checkbox' name='skjult' value='1' " . ($row["skjult"] ? "checked" : "") . " onchange='this.form.submit()'> Skjult</label></form>";
                echo "<a href='editor.php?slet=" . $row["id"] . "' onclick='return confirm(\"Er du sikker på, at du vil slette denne slide?\")'>Slet</a>"; // Tilføjet slet link med bekræftelse
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>Ingen slides fundet i databasen. Opret en ny slide i 'Opret Ny Slide' tab'en.</p>";
        }
    } else {
        echo "<p style='color: red;'>Fejl ved hentning af slides fra databasen: " . $conn->error . "</p>";
    }
    ?>

    <h2>Rediger Besked</h2>

    <form action="editor.php" method="post">
        <label for="besked">Message (Shown at the top of the info screen):</label><br>
        <?php
        // Hent den aktuelle besked fra databasen
        $sql_select_message = "SELECT besked FROM konfiguration LIMIT 1";
        $result_select_message = $conn->query($sql_select_message);
        $besked = ""; // Standardværdi
        if ($result_select_message) { // Tjek om forespørgslen lykkedes
            if ($result_select_message->num_rows > 0) {
                $row_message = $result_select_message->fetch_assoc();
                $besked = htmlspecialchars($row_message["besked"]);
            } else {
                echo "<p style='color: orange;'>Advarsel: 'konfiguration' tabellen er tom eller findes ikke. Opret en række i den for at gemme beskeden.</p>";
            }
        } else {
            echo "<p style='color: red;'>Fejl ved hentning af besked fra databasen: " . $conn->error . "</p>";
        }
        ?>
        <textarea id="besked" name="besked" rows="4" cols="50"><?php echo $besked; ?></textarea><br><br>
        <input type="submit" name="opdater_besked" value="Opdater Besked">
    </form>

    <form action="http://wt.mapsil.dk/dash/support">
        <input type="submit" value="Få Hjælp!" />
    </form>
</div>

<?php
// Luk databaseforbindelsen
$conn->close();

ob_end_flush(); // Send output og slut buffering
?>
