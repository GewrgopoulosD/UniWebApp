<?php
session_start();

require_once "controler/UserControler.php";
require_once "models/Users.php";
require_once "models/Students.php";
require_once "models/Teachers.php";


if (isset($_SESSION['user_id'])) { // Check if user is already logged in
  header("Location: dashboard.php");
  exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') { //take the data from form and 
    $controller = new UserControler();// make an object of controller to handle the actions 
    $mode = $_POST["mode"] ?? '';
    
    if ($mode === 'login') { //if the action is login, call the login function from controller 
        $controller->login();
    } elseif ($mode === 'signup') { // or signup
        $controller->signup();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/auth.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <script type="module" src="js/Auth.js" defer></script>

  <title>Education University | Login</title>
</head>

<body>
  <header>
    <div class="Logo">
      <a href="index.php"><img id="logoImage" src="photos/Logo.png" alt="university test icon" /></a>
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
      <li>
        <img class="menuItem active" id="photoMenu" src="photos/Logo.png" alt="university test icon" />
      </li>
      <li><a class="menuItem" href="index.php">Home</a></li>
      <li><a class="menuItem" href="About.php#UnderPrograms">Studies</a></li>
      <li><a class="menuItem" href="About.php">About us</a></li>
      <li><a class="menuItem" href="About.php#locationHeader">Contact</a></li>
      <li><a class="menuItem login" href="Auth.php?mode=login">Login</a></li>
      <li><a class="menuItem" href="Auth.php?mode=signup">Sign up</a></li>
    </ul>
  </nav>

  <div class="mForm">
    <img src="photos/library.jpg" alt="test" />
    <div id="authCont"></div>
  </div>

  <footer>
    <a href="index.php"><img id="footerImg" src="photos/Logo.png" alt="university test icon" /></a>
    <div class="foot">
      <p>&copy; 2025 Education University</p>
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