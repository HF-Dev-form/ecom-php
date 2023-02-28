



  



<div class="d-flex flex-column fixed-top p-3 text-white bg-dark p-2" id="nav-back" style="width: 280px;">

     <span class="navbar-brand brand text-white text-center"><a class="nav-link" href="<?= URL ?>-index.php">Paris Sweet Garden</a></span>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto p-4">
      <li class="nav-item">
        <a href="<?= URL ?>admin/" class="nav-link active" aria-current="page">
         Accueil backoffice
        </a>
      </li>
      <li>
        <a href="<?= URL ?>admin/produit/" class="nav-link text-white">
         Gestion des produits
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Gestion des membres
        </a>
      </li>
      <li>
        <a href="#" class="nav-link text-white">
          Gestions des commentaires
        </a>
      </li>
     
    </ul>
    <hr>
    <div class="dropdown">
      <a href="<?php URL ?>profile.php" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="<?php if(isset($_SESSION['membre'])) echo $_SESSION['membre']['image']  ?>" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><a class="dropdown-item" href="<?= URL ?>deconnexion.php">Deconnexion</a></li>

      </ul>
    </div>
  </div>

    <div class="back-container container-fluid col-8">

<script>


        const nav = document.querySelector('#nav-back')

        document.addEventListener("mouseover", (e) => {


            // console.log(e.pageX);
            // console.log(window.scrollY);
            if(e.pageX < 300)
            {
               nav.classList.add("nav-down");
               nav.classList.remove("nav-up");

            }else
            {
                nav.classList.add("nav-up");
                nav.classList.remove("nav-down");
            }
        })

  

</script>

<style>

    .nav-down{
      opacity: 1;
      left:0;
      transition: all .8s ease-in;
    }

    .nav-up{
      opacity: 0;
      left: -300px;
      transition: all .8s ease-in;
    }

    .back-container{
      

    }

</style>


