<!DOCTYPE html>
<html>
<head>
    <title>A FireDash DashBoard</title>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap">
    <link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet" type="text/css">
    <style>
        body {
            background-color: #f0f0f0;
            margin: 0;
            overflow: hidden;
            display: flex;
            height: 100vh;
            font-family: 'Lato', sans-serif;
        }

        #slide_container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            width: 100%;
        }

        #sidebar {
            width: 250px;
            background-color: #ddd;
            padding: 20px;
            box-sizing: border-box;
            overflow: auto;
            text-align: center;
            font-family: 'Lato', sans-serif;
        }

        .billede_slide {
            text-align: center; /* Center indholdet vandret */
        }

        .billede_slide h1 {
            font-size: 2em;
            margin-bottom: 10px;
            font-family: 'Lato', sans-serif;
        }

        .billede_slide img {
            max-width: 100%;
            max-height: 80%; /* Giv plads til overskriften */
            object-fit: contain;
            margin-bottom: 10px; /* Plads mellem billede og evt. beskrivelse */
        }

        .tekst_slide {
            text-align: center;
            padding: 20px;
            font-family: 'Lato', sans-serif;
        }

        .tekst_slide h1 {
          font-family: 'Lato', sans-serif;
        }

        .fødselsdag_slide {
            text-align: center;
            position: relative;
            width: 100%;
            height: 100%;
            font-family: 'Lato', sans-serif;
        }

        .fødselsdag_slide::before {
            content: "";
            background-image: url('fødselsdag_baggrund.png'); /* Baggrundsbillede */
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.8; /* Juster gennemsigtighed efter behov */
            z-index: 1; /* Placer baggrunden bagved teksten */
        }

        .fødselsdag_slide h1 {
            font-size: 2em;
            margin-bottom: 10px;
            position: relative; /* Placér teksten oven på baggrunden */
            z-index: 2;
            font-family: 'Lato', sans-serif; /* Definer font på h1 i fødselsdag_slide */
        }

        .fødselsdag_slide p {
            font-size: 1.5em;
            position: relative; /* Placér teksten oven på baggrunden */
            z-index: 2;
            font-family: 'Lato', sans-serif; /* Definer font på p i fødselsdag_slide */
        }

        /* Styling for urene */
        #digitalClock {
            font-size: 1.5em;
            margin-bottom: 10px;
            font-family: 'Lato', sans-serif;
            text-align: center;
        }

        /* Styling for logo */
        #logo {
            max-width: 150px;
            margin-bottom: 20px;
        }

        /* Analog Clock CSS (Indsat her) */
        .clock {
            background: #ececec;
            width: 150px; /* Mindre størrelse */
            height: 150px; /* Mindre størrelse */
            margin: 10px auto 0; /* Justeret margin */
            border-radius: 50%;
            border: 7px solid #333; /* Mindre border */
            position: relative;
            box-shadow: 0 1vw 2vw -0.5vw rgba(0,0,0,0.8); /* Mindre skygge */
            font-family: 'Arial', sans-serif; /* Definer font på clock */
        }

        .dot {
            width: 7px; /* Mindre dot */
            height: 7px; /* Mindre dot */
            border-radius: 50%;
            background: #ccc;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            position: absolute;
            z-index: 10;
            box-shadow: 0 1px 2px -0.5px black; /* Mindre skygge */
        }

        .hour-hand {
            position: absolute;
            z-index: 5;
            width: 2px; /* Mindre hånd */
            height: 32px; /* Mindre hånd */
            background: #333;
            top: 39px; /* Justeret position */
            transform-origin: 50% 36px; /* Justeret position */
            left: 50%;
            margin-left: -1px; /* Justeret margin */
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
        }

        .minute-hand {
            position: absolute;
            z-index: 6;
            width: 2px; /* Mindre hånd */
            height: 50px; /* Mindre hånd */
            background: #666;
            top: 23px; /* Justeret position */
            left: 50%;
            margin-left: -1px; /* Justeret margin */
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
            transform-origin: 50% 53px; /* Justeret position */
        }

        .second-hand {
            position: absolute;
            z-index: 7;
            width: 1px; /* Mindre hånd */
            height: 60px; /* Mindre hånd */
            background: gold;
            top: 13px; /* Justeret position */
            lefT: 50%;
            margin-left: -0.5px; /* Justeret margin */
            border-top-left-radius: 50%;
            border-top-right-radius: 50%;
            transform-origin: 50% 63px; /* Justeret position */
        }

        span {
            display: inline-block;
            position: absolute;
            color: #333;
            font-size: 11px; /* Mindre font */
            font-family: 'Arial', sans-serif; /* Definer font på span */
            font-weight: 700;
            z-index: 4;
        }

        .h12 {
            top: 15px; /* Justeret position */
            left: 50%;
            margin-left: -4.5px; /* Justeret margin */
        }
        .h3 {
            top: 70px; /* Justeret position */
            right: 15px; /* Justeret position */
        }
        .h6 {
            bottom: 15px; /* Justeret position */
            left: 50%;
            margin-left: -2.5px; /* Justeret margin */
        }
        .h9 {
            left: 16px; /* Justeret position */
            top: 70px; /* Justeret position */
        }

        .diallines {
            position: absolute;
            z-index: 2;
            width: 1px; /* Mindre linje */
            height: 7px; /* Mindre linje */
            background: #666;
            left: 50%;
            margin-left: -0.5px; /* Justeret margin */
            transform-origin: 50% 75px; /* Justeret position */
        }
        .diallines:nth-of-type(5n) {
            width: 2px; /* Større linje */
            height: 12px; /* Større linje */
        }

        .info {
            position: absolute;
            width: 60px; /* Mindre info box */
            height: 10px; /* Mindre info box */
            border-radius: 3.5px; /* Mindre border radius */
            background: #ccc;
            text-align: center;
            line-height: 10px; /* Justeret line height */
            color: #000;
            font-size: 8px; /* Mindre font */
            top: 100px; /* Justeret position */
            left: 50%;
            margin-left: -30px; /* Justeret margin */
            font-family: "Poiret One";
            font-weight: 700;
            z-index: 3;
            letter-spacing: 1.5px; /* Mindre letter spacing */
        }
        .date {
            top: 40px;
        }
        .day {
            top: 100px;
        }

        /* Generel iframe styling */
        .embed_slide {
            width: 100%;
            height: 100%;
        }

        .embed_slide iframe {
            width: 100%;
            height: 100%;
            border: none; /* Fjern standard border */
        }
    </style>
</head>
<body>

<div id="slide_container">
    <!-- Indholdet af den aktuelle slide vil blive indsat her -->
</div>

<div id="sidebar">
    <img id="logo" src="https://logo" alt="Logo"> <!-- Replace with your logo -->
    <div id="digitalClock"></div>

    <!-- Analog Clock HTML (Indsat her) -->
    <div class="clock">
        <div>
            <div class="info date"></div>
            <div class="info day"></div>
        </div>
        <div class="dot"></div>
        <div>
            <div class="hour-hand"></div>
            <div class="minute-hand"></div>
            <div class="second-hand"></div>
        </div>
        <div>
            <span class="h3">3</span>
            <span class="h6">6</span>
            <span class="h9">9</span>
            <span class="h12">12</span>
        </div>
        <div class="diallines"></div>
    </div>

    <p id="besked_tekst">
        <!-- Beskeden fra databasen vil blive indsat her -->
    </p>
</div>

<script>
    let slides = [];
    let currentSlideIndex = 0;

    function loadSlides() {
        fetch('get_slides.php')
            .then(response => response.json())
            .then(data => {
                slides = data;
                showSlide();
            })
            .catch(error => console.error('Fejl ved indlæsning af slides:', error));
    }

    function showSlide() {
        if (slides.length === 0) {
            document.getElementById('slide_container').innerHTML = '<p>Ingen slides fundet.</p>';
            return;
        }

        const slide = slides[currentSlideIndex];
        console.log("Current slide:", slide);
        let slideContent = '';

        if (slide.type === 'billede') {
            let imageUrl = slide.billede_url;
            if (slide.billede_filnavn) {
                imageUrl = 'uploads/' + slide.billede_filnavn;
            }
            slideContent = `<div class="billede_slide">
                                <h1>${slide.billede_overskrift ? slide.billede_overskrift : ''}</h1>
                                <img src="${imageUrl}" alt="Billede">
                             </div>`;
        } else if (slide.type === 'tekst') {
            slideContent = `<div class="tekst_slide"><h1>${slide.overskrift}</h1><p>${slide.tekst}</p></div>`;
        } else if (slide.type === 'fødselsdag') {
            console.log("Fødselsdag slide fundet");
            let navneListe = '';
            if (slide.fødselsdag_navne) {
                try {
                    const navne = slide.fødselsdag_navne.split(',').map(navn => navn.trim());
                    console.log("Navne array:", navne);
                    navneListe = navne.join('<br>');
                } catch (error) {
                    console.error("Fejl ved oprettelse af fødselsdagsliste:", error);
                    navneListe = '<p style="color: red;">Fejl ved visning af fødselsdagsnavne.</p>';
                }
            } else {
                navneListe = '<p>Ingen navne angivet.</p>';
            }
            slideContent = `<div class="fødselsdag_slide"><h1>Tillykke med fødselsdagen!</h1><p>${navneListe}</p></div>`;
        } else if (slide.type === 'pdf') {
            slideContent = `<div class="embed_slide">
                             <iframe is="x-frame-bypass" src="${slide.pdf_url}"></iframe>
                           </div>`;
        }

        document.getElementById('slide_container').innerHTML = slideContent;

        setTimeout(nextSlide, slide.visningstid * 1000);
    }

    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % slides.length;
        showSlide();
    }

    // Funktion til at opdatere den digitale klok
    function updateDigitalClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        document.getElementById("digitalClock").textContent = `${hours}:${minutes}:${seconds}`;
    }

    // Analog Clock JavaScript (Indsat her)
    var dialLines = document.getElementsByClassName('diallines');
    var clockEl = document.getElementsByClassName('clock')[0];

    for (var i = 1; i < 60; i++) {
        clockEl.innerHTML += "<div class='diallines'></div>";
        dialLines[i].style.transform = "rotate(" + 6 * i + "deg)";
    }

    function clock() {
        var weekday = [
                "Sunday",
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ],
            d = new Date(),
            h = d.getHours(),
            m = d.getMinutes(),
            s = d.getSeconds(),
            date = d.getDate(),
            month = d.getMonth() + 1,
            year = d.getFullYear(),

            hDeg = h * 30 + m * (360/720),
            mDeg = m * 6 + s * (360/3600),
            sDeg = s * 6,

            hEl = document.querySelector('.hour-hand'),
            mEl = document.querySelector('.minute-hand'),
            sEl = document.querySelector('.second-hand'),
            dateEl = document.querySelector('.date'),
            dayEl = document.querySelector('.day');

        var day = weekday[d.getDay()];

        if(month < 9) {
            month = "0" + month;
        }

        hEl.style.transform = "rotate("+hDeg+"deg)";
        mEl.style.transform = "rotate("+mDeg+"deg)";
        sEl.style.transform = "rotate("+sDeg+"deg)";
        dateEl.innerHTML = date+"/"+month+"/"+year;
        dayEl.innerHTML = day;
    }

    // Run clock() every 100 milliseconds
    setInterval(clock, 100);

    // Initialiser digital klok
    updateDigitalClock();

    // Run digitalClock every 1000 milliseconds (1 second)
    setInterval(updateDigitalClock, 1000);


    // Funktion til at indlæse beskeden fra databasen
    function loadBesked() {
        fetch('get_besked.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById("besked_tekst").textContent = data;
            })
            .catch(error => console.error('Fejl ved indlæsning af besked:', error));
    }

    // Indlæs beskeden
    loadBesked();
    // Indlæs slides, når siden indlæses
    loadSlides();
</script>
<script src="x-frame-bypass.js"></script>
</body>
</html>
