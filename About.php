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
    <title>Document</title>
    <link rel="stylesheet" href="css/AboutCss.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.userRole = <?php echo json_encode($userType); ?>;
        window.username = <?php echo json_encode($username); ?>;
    </script>
    <script type="module" src="js/about.js" defer></script>
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
            <li><a class="menuItem StudiesAboutBtn" href="About.php#UnderPrograms">Studies</a></li>
            <li><a class="menuItem" href="About.php">About us</a></li>
            <li><a class="menuItem ContactAboutBtn" href="About.php#locationHeader">Contact</a></li>
            <li><a class="menuItem login" href="Auth.php?mode=login">Login</a></li>
            <li><a class="menuItem signup" href="Auth.php?mode=signup">Sign up</a></li>
        </ul>
    </nav>
    <main>
        <div id="map"></div>
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