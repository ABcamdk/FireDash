<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireDash</title>
    <link rel="stylesheet" href="style.css">
    <style>
    .logout-link {
        text-align: center; /* Centrerer teksten */
        margin-top: 20px; /* Juster efter behov */
    }

    .logout-link a {
        color: #007bff; /* Blå farve, kan justeres */
        text-decoration: none; /* Fjerner understregning */
    }

    .logout-link a:hover {
        text-decoration: underline; /* Tilføjer understregning ved hover */
    }

        /* Tilføjet inline styling for at sikre layout */
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px; /* Justér efter behov */
        }

        .glass-button {
            display: inline-block;
            padding: 12px 24px;
            margin: 0 10px;
            border: none;
            background: rgba(255, 255, 255, 0.2); /* Gennemsigtig hvid baggrund */
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Let skygge */
            color: white;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .glass-button:hover {
            background: rgba(255, 255, 255, 0.3); /* Lidt mere solid farve ved hover */
        }

        .glass-button:active {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Mindre skygge ved klik */
            transform: translateY(1px); /* Lille "tryk"-effekt */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-box">
            <div class="welcome-content">
                <div class="logo-container">
                    <img src="https://company.com/your_logo.svg" alt="Logo">
                </div>
                <div class="welcome-text">
                    <h1>Welcome to FireDash at "You Company"</h1>
                    <p>Please select an action:</p>
                </div>

                <div class="button-container">
                    <a href="editor.php" class="glass-button">Editor</a>
                    <a href="display.php" class="glass-button">Preview/Present</a>
                    <a href="help.pdf" class="glass-button">Guide</a>
                </div>
                <div class="logout-link">
                   <a href="..">Log ud</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
