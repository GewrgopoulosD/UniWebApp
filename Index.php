<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";

$userControler = new UserControler(); //create an instance from userControler
$userData = $userControler->getCurrentUser(); //call the method getCurrrentUser

$userType = $userData ? $userData['userType'] : null;//if find sth keep them on these var to take them in js 
$username = $userData ? $userData['username'] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/IndexCss.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    <script>
        window.userRole = <?php echo json_encode($userType); ?>;
        window.username = <?php echo json_encode($username); ?>;
    </script>
    <script type="module" src="js/Index.js"></script>
    <title>Education University</title>
</head>

<body>

    <header>
        <div class="Logo">
            <a href="index.php"><img id="logoImage" src="photos/Logo.png" alt="university test icon"></a>
        </div>
    </header>
    <nav class="burger">
        <button class="hamburger">
            <i class="material-icons">menu</i>
        </button>
        <button class="hamburgerC">
            <i class="material-icons">close</i>
        </button>
        <ul class="menu">
            <li><img class="menuItem active" id="photoMenu" src="photos/Logo.png" alt="university test icon"></li>
            <li><a class="menuItem" href="index.php">Home</a></li>
            <li><a class="menuItem" href="About.php#UnderPrograms">Studies</a></li>
            <li><a class="menuItem" href="About.php">About us</a></li>
            <li><a class="menuItem" href="About.php#locationHeader">Contact</a></li>
            <li><a class="menuItem login" href="Auth.php?mode=login">Login</a></li>
            <li><a class="menuItem signup" href="Auth.php?mode=signup">Sign up</a></li>
        </ul>
    </nav>
    <main>
        <h1>Welcome to Education University</h1>
        <div class="firstDiv">
            <img id="indigenousEducation" src="photos/educationMain.jpg" alt="indigenous education phto">
            <p class="left blackBox edu">Education is the key to a balanced future</p>
            <p class="mainFirstMeeting">Education University is a modern academic institution dedicated to education,
                research, and innovation.
                Since its establishment in 2005, the university has offered high-quality study programs in the fields of
                Computer Science,
                Engineering, Economics, and Social Sciences.
            </p>
            <button type="button" id="readMore">Read more about us.</a></button>
        </div>
        <h3 id="studyWU">Study with Us</h3>
        <div class="campusPhoto">
            <p id="ourCampuses">Our Campus</p>
            <img id="leftSide" src="photos/hanze1.jpg" alt="university photo">
            <p class="mainFirstMeeting logo2">The university campus offers a modern, well-structured learning
                environment designed to support both
                academic and personal development. Its architecture and facilities foster collaboration, innovation,
                and a strong sense of community.</p>
            <img id="rightSide" src="photos/hanze2.jpg" alt="university photo">
        </div>
        <p id="oPrograms">Explore Our Programs</p>
        <div class="secInside">
            <p>Undergraduate</p>
            <button type="button" id="programsP">Find your course</a></button>
        </div>
        <div class="thirdInside">
            <p>Postgraduate</p>
            <button type="button" id="programsM">Find your course</a></button>
        </div>
        <div class="location">
            <p id="vOc">Visit Our Campus</p>
            <p class="mainFirstMeeting">Locate our campus on the map and discover how to reach us,
                while exploring an environment designed for learning and innovation.</p>
            <div id="map"></div>
        </div>
    </main>
    <footer>
        <a href="index.php"><img id="footerImg" src="photos/Logo.png" alt="university test icon"></a>
        <div class="foot">
            <p> &copy; 2025 Education University</p>
            <ul>
                <li><a href="About.php">About us</a></li>
                <li><a href="About.php#UnderPrograms">Studies</a></li>
                <li><a href="About.php#locationHeader">Contact</a></li>
                <li><a href="Auth.php?mode=login">Portal</a></li>
            </ul>
        </div>
    </footer>
</body>

</html>