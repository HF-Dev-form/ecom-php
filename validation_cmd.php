<?php
require_once("incs/init.php");






require_once("incs/header.php");
require_once("incs/nav.php");
?>

    <div class="container">
            <div class="my-5"></div>
            <div style="background-color:lightgreen; color:white;" class="p-5 col-7 fst-bold mx-auto text-center my-5">
                    <h1 class="display-3 text-white">Félicitation, votre commande est enregistré</h1>
                    <p class="lead">Vous recevrai un email à l'adresse <strong><?= $_SESSION['membre']['email']?></strong> lors de la confirmation</p>
                    <hr class="my-2">
                    <p>Retour sur votre page profile</p>
                    <p class="lead">
                        <a class="btn btn-primary btn-lg" href="profile.php" role="button">Mon compte</a>
                    </p>
            </div>
    
    </div>


<?php
require_once("incs/fonctions.php");