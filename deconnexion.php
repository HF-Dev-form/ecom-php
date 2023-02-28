<?php
require_once('incs/init.php');



require_once('incs/header.php');
require_once('incs/nav.php');
?>

<div class="container">

        <h1 class="text-center my-5">Deconnexion</h1>


            <div class="p-5 bg-light col-7 mx-auto text-center my-5">
                    <h1 class="display-3 text-success">Bonjour <?php if(connect()) echo $_SESSION['membre']['pseudo'] ?></h1>
                    <p class="lead">Etes-vous certain de vouloir vous déconnecter?</p>
                    <hr class="my-2">
                    <p class="lead">
                        <form action="form_deconnexion.php">
                                <button type="submit" class="btn btn-primary">Deconnexion</button>
                        </form>
                    </p>
            </div>

</div>


<?php
require_once('incs/footer.php');