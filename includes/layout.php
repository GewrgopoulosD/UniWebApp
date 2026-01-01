<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="./css/dashCss.css" />
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <title><?php echo $pageTitle ?? 'Education University'; ?></title>
  <?php
  if (isset($pageCssFiles)) {
    foreach ((array) $pageCssFiles as $cssFile) {
      echo '<link rel="stylesheet" href="./css/' . htmlspecialchars($cssFile) . '" />';
    }
  }
  ?>

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

      <?php if (isset($_SESSION['user_id'])): ?>
        <li><a class="menuItem" href="dashboard.php">Dashboard</a></li>
        <li><a class="menuItem logout" href="?action=logout">Logout</a></li>
      <?php else: ?>
        <li><a class="menuItem login" href="Auth.php?mode=login">Login</a></li>
        <li><a class="menuItem signup" href="Auth.php?mode=signup">Sign up</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <div class="dashHero">
    <img src="photos/library.jpg" alt="test" />
    <div id="<?php echo $mainDivId ?? 'mainDiv'; ?>">
      <?php echo $content; ?>
    </div>
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

  <?php if (isset($jsFile)): ?>
    <script>
      window.userRole = <?php echo json_encode($userType ?? null); ?>;
      window.username = <?php echo json_encode($username ?? null); ?>;
      <?php if (isset($additionalJsVars))
        echo $additionalJsVars; ?>
    </script>
    <script type="module" src="js/<?php echo $jsFile; ?>"></script>
  <?php endif; ?>
</body>

</html>