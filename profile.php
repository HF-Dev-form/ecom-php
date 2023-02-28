<?php 
require_once("incs/init.php");

if(!connect())
{
    header('location: connexion.php');
}

require_once("incs/header.php");
require_once("incs/nav.php");
?>


    <div class="container">

            <h1 class="text-center my-5">Vos informations personnelles</h1>

           <div class="card mx-auto text-center col-6 rounded shadow-lg my-5">
                <div class="bg-image hover-overlay ripple" data-mdb-ripple-color="light">
                    <img src="http://via.placeholder.com/400X280" class="img-fluid p-2" />
                <div class="card-body">
                    <h4 class="card-title"><span class="text-start"><?php if($_SESSION['membre']['nom'] == 'femme'):  echo 'Mme'?>  <?php elseif($_SESSION['membre']['nom'] == 'autre'): echo 'No genre' ?> <?php else: echo 'Mr' ?> <?php endif ?>  </span> <?php echo $_SESSION['membre']['nom'] . ' ' . $_SESSION['membre']['prenom']; ?></h4>
                        <p class="card-text">
                        </p>
                        <p class="card-text">
                        </p>
                        <p class="card-text">
                        <?php echo $_SESSION['membre']['adresse'] . ' ' . $_SESSION['membre']['code_postal'] . ' ' .  $_SESSION['membre']['ville'] ?>
                        </p>
                        <?php if(is_admin()): ?>
                    <a href="admin/index.php" class="btn btn-primary">Aller vers le back-office</a>
                    <?php endif ?>
                </div>
                    <div class="card-footer">2 days ago</div>
                </div>
        
            </div>

    </div>  



<?php
require_once("incs/footer.php");