<div id="header-img"></div>

<!-- NAVBAR -->
<!-- partial:index.partial.html -->
<section class="navigation">

  <div class="nav-container">
    <div class="brand">
      <a href="./index.php">OstEo</a>
    </div>
    <nav>
      <div class="nav-mobile"><a id="nav-toggle" href="#!"><span></span></a></div>
      <ul class="nav-list">
        <li>
          <a href="./index.php">Accueil</a>
        </li>
        <?php
        if (isset($_SESSION['username']) && (isset($_SESSION['date']))) {
        } else {
        ?>
          <li>
            <a href="./login.php">Connexion</a>
          </li>
          <li>
            <a href="./sign.php">Inscription</a>
          </li>
        <?php
        }
        ?>

        <li>
          <a href="#!">A propos</a>
        </li>
        <li>
          <a href="#!">Contact</a>
        </li>
      </ul>
    </nav>
  </div>
</section>
<!-- NAVBAR -->