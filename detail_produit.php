<?php
require_once('incs/init.php');
 
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
                        //Si le rowcount de la requête retourne 0 ligne, cela veut dire que le produit n'existe pas en bdd
                            
                       $_SESSION['erreur_produit'] = '<div class="alert alert-warning alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         <strong>Ce produit n\'est pas disponible</strong>
                        </div>';

                          header("location: " . URL . "produit.php");
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

require_once('incs/header.php');
require_once('incs/nav.php');
?>


            <div class="container">

                    <h1 class="my-5 text-center"><?= $titre ?> </h1>
                     <div class="col-9 mx-auto">
                        <div class="card">
                             <img src="<?= $photo?>" class="card-img-top img-fluid p-2" alt="<?= $titre ?>">
                                <div class="card-body text-center text-dark fst-bold" >
                                    <h5 class="card-title"><?= $produit['titre'] ?></h5>
                                    <hr>
                                    <p class="card-text"><?= $description?></p>
                                    <hr>
                                    <p class="card-text ">Catégorie du produit: <?php echo $categorie ?></p>
                                    <h5 class="card-text text-center"><?= $produit['prix'] ?>€ </h5>
                                    <p class="card-text"> Couleur <?php echo $couleur ?></p>
                                     
                                    <div class="nav-link text-center">
                                     <?php if($produit['stock'] <= 5  && $produit['stock'] !== 0): ?>  
                                     <p class="text-danger fst-italic">Il ne reste plus que <?= $produit['stock'] ?> exemplaires(s) disponible</p>
                                    
                                    <?php elseif($produit['stock'] > 5 ): ?>
                                     <p class="text-success fst-italic">Quantité disponible <?= $produit['stock'] ?></p>
                                    <?php endif?>
                                    <?php if($produit['stock'] > 0): ?>

                                     <hr>   
                                     <form class="form-inline d-flex justify-content-center mb-3 ms-2" action="panier.php" method="POST">
                                        <input type="hidden" name="id_produit" value="<?=$produit['id_produit']?> ">
                                        <div class="form-group">
                                            <select class="form-control" name="quantite" id="">
                                                <?php for($i = 1; $i <= $produit['stock'] && $i <= 10; $i++ ): ?>
                                                    <option value="<?= $i ?>"><?= $i ?></option>
                                                <?php endfor ?>
                                            </select>
                                        </div>
                                         <button type="submit" name="aujout_panier" class="btn btn-sm  btn-success me-1"> <i class="fa-solid fa-cart-arrow-down"></i></button>
                                     </form>   
                                    <?php endif ?>
                                    <a href="<?php URL ?>produit.php" class="btn btn-sm btn-primary">Revenir à la boutique</a>
                                    </div>
                                </div>
                        </div>
                    </div>


                    
                    <div class="card card-outline-secondary my-5 col-7 mx-auto">
                    <div class="card-header">
                        Product Reviews
                    </div>
                    <div class="card-body">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <a href="#" class="btn btn-success">Leave a Review</a>
                    </div>
                    </div>
            
            </div>





<?php
require_once('incs/footer.php');
