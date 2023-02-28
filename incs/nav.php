<?php
require_once('incs/init.php');
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary" id="nav">
  <div class="container-fluid">
    <a class="navbar-brand pe-2" href="#">PARIS SWEET GARDEN</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php if (pageActive("-index.php")) {
              ;
          } ?>" href="-index.php">Accueil
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="produit.php">Produits</a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="#">Contact</a>
        </li>
      </ul>
   
        <nav class="navbar py-0">
            <ul class="navbar-nav navbar-right">
                   <?php if (!empty($_SESSION["panier"]["id_produit"])): ?>
                       <li class="nav-item">
                        <small>
                          <a href="panier.php" class="nav-link text-white">
                            <i class="fa-solid fa-cart-shopping"></i>
                             <?php if(totalPanier()): ?>
                              <?= totalPanier() ?> €
                              <?php endif ?>
                          </a>
                        </small>
                      </li> 
                      <?php endif ?>
                   <?php if (!connect()): ?>
                    <li class="nav-item">
                              <small><a href="inscription.php" class="nav-link text-white">Inscription</a> </small>
                    </li>
                    <li class="nav-item">
                              <small><a href="connexion.php" class="nav-link text-white" id="connect">Connexion</a> </small>
                    </li>   
                      <?php else : ?>
                      <li class="nav-item" id="deco">
                            <small><a onclick="return(confirm('aller sur la page de deconnexion?'))" href="deconnexion.php" class="nav-link text-white"><i class="fas fa-power-off border-bottom border-end border-dark rounded p-1 mb-1"></i></a> </small>
                            <span id="msg-off"></span>
                      </li>
                        <li class="nav-item">
                              <a class="nav-link text-white <?php if (pageActive("profile.php")) {
                                  ;
                              } ?>" href="profile.php">Mon compte
                                    <strong class="text-success"><?php if(isset($_SESSION['membre'])) echo $_SESSION['membre']['pseudo'] ?></strong>
                                </a>
                        </li>
                      
                      <?php endif ?>
                      <?php if (is_admin()): ?>
                      <li class="nav-item dropdown">
                          <a class="nav-link  text-white" href="<?= URL ?>/admin">Back-office</a>
                      </li>
                     <?php endif ?>
              </ul>
           </nav>
       

      <!-- <form class="d-flex">
        <input class="form-control me-sm-2" type="text" placeholder="Search">
        <button class="btn btn-secondary my-2 my-sm-0" type="submit">Search</button>
      </form> -->

    </div>
  </div>
</nav>

<style>
  .nav-down {
    opacity: 0.5;
    position: fixed;
    background-color: lightgreen !important;
    top: -200px;
    transition: all 1s ease-in;
}

.nav-up {
    opacity: 1;
    top: 0;
    transition: all .8s ease-in-out;
}
</style>

<script>
  window.onload = function() {

    let nav = document.querySelector('#nav');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 120) {
            nav.classList.add("nav-down")
            nav.classList.remove("nav-up")
        } else if (window.scrollY < 100) {
            nav.classList.remove("nav-down")
            nav.classList.add("nav-up")
        }
    
    })

}
</script>


<main class="container-fluid px-0 container-principal">