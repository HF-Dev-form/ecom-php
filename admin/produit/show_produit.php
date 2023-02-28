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
                    $sql = "SELECT * FROM produit WHERE id_produit = $id";

                    //on prépare la requête, cela instancie au passage un PDOStatement
                    $statement = $pdo->prepare($sql);

                    //Auncun bind à faire, on execute la requête
                    $statement->execute();

                    //On récupère grâce au fetchAll() tous les produits stockés en BDD
                    $produit = $statement->fetch(PDO::FETCH_ASSOC);

                    //Nous récupérons toutes les données du produit dans la variable $produit

                    //Extraction des données tableau
                    extract($produit);

                    if($statement->rowCount() === 0)
                    {
                        //Si le rowncount de la requête retourne 0 ligne, cela veut dire que le produit n'existe pas en bdd
                            
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
    }
    else
    {
         $_SESSION['erreur_produit'] = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Ce produit n\'existe pas</strong>
           </div>';

           header("location: " . URL . "admin/produit/");
    }

require_once('../../incs/header.php');
require_once('../incs/back_nav.php');
?>


            <div class="container">

                    <h1 class="my-5 text-center">Produit n° <?= $id_produit ?> </h1>
                    <h3 class="text-center my-3"><?= $titre ?></h3>

                     <div class="col">
                        <div class="card">
                             <img src="<?= $photo?>" class="card-img-top img-fluid p-2" alt="<?= $titre ?>">
                                <div class="card-body text-center text-dark fst-bold" >
                                    <h5 class="card-title"><?= $produit['titre'] ?></h5>
                                    <hr>
                                    <p class="card-text"><?= $description?></p>
                                    <hr>
                                    <p class="card-text ">Catégorie du produit: <?php echo $categorie ?></p>
                                    <p class="card-text text-center"><?= $produit['prix'] ?>€ - <?= $produit['stock'] ?>en stock</p>
                                    <p class="card-text">Public <?php echo $public ?> - Taille: <?php echo $taille ?> - Couleur<?php echo $couleur ?></p>
                                    <div class="nav-link text-center">
                                    <a href="<?php URL ?>index.php? ?>" class="btn btn-sm btn-primary">Revenir à l'accueil</a>
                                    <a href="<?php URL ?>edit_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm  btn-warning"><i class="fa-solid fa-pen"></i></a>
                                    <a onclick="return(confirm('Etes-vous certain de vouloir supprimer définitivement ce produit?'))" href="<?php URL ?>delete_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm  btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </div>
                        </div>
                    </div>
            
            </div>





<?php
require_once('../../incs/footer.php');
