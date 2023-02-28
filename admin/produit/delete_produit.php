<?php
require_once('../../incs/init.php');
    //On contrôle l'acces au backoffice    
    if(!is_admin())
    {
        header("location:" . URL .  "-index.php");
    }

    //On contrôle que l'on reçoit bien un id correspondant à un produit existant en bdd et on vérifie également qu'il est bien de type numérique
    if($_GET)
    {
        if(isset($_GET['id']) && !empty($_GET['id']))
        {
                if(ctype_digit($_GET['id']))
                {
                    $id = $_GET['id'];

                    //On tock la requête
                    $sql = "DELETE FROM produit WHERE id_produit = $id";

                    //on prépare la requête, cela instancie au passage un PDOStatement
                    $statement = $pdo->prepare($sql);

                    //Auncun bind à faire, on execute la requête
                    $statement->execute();

                    
                       $_SESSION['success_produit'] = '<div style="background-color:lightGreen; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         <strong>Le produit n° ' . $id . ' a bien été supprimé</strong>
                        </div>';

                          header("location: " . URL . "admin/produit/");

                }
                else
                {
                       $_SESSION['erreur_produit'] = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         <strong>Ce produit n\'existe pas</strong>
                        </div>';

                          header("location: " . URL . "admin/produit/");
                }

          
        }
        else
        {
            $_SESSION['erreur_produit'] = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Ce produit n\'existe pas</strong>
           </div>';

           header("location: " . URL . "admin/produit/");
        }
    }
    else
    {
         $_SESSION['erreur_produit'] = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Ce produit n\'existe pas</strong>
           </div>';

           header("location: " . URL . "admin/produit/");
    }