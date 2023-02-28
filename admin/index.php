<?php
require_once('../incs/init.php');

    if(!is_admin())
    {
        header("location:" . URL .  "-index.php");
    }

    print_r($_SERVER['REMOTE_ADDR']);

require_once('../incs/header.php');
require_once('incs/back_nav.php');
?>



        <div class="container">
        
                <h1 class="my-5 text-center">Bienvenue dans votre backoffice</h1>

                <a href="<?php URL ?>../-index.php" class="btn btn-primary text-center mx-auto">Quitter le backoffice</a>

               
        </div>
    

<?php
require_once('incs/back_footer.php');
