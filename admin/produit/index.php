<?php
require_once('../../incs/init.php');
    //On contrôle l'acces au backoffice    
    if(!is_admin())
    {
        header("location:" . URL .  "-index.php");
    }

    //On tock la requête
    $sql = "SELECT * FROM produit";

    //on prépare la requête, cela instancie au passage un PDOStatement
    $statement = $pdo->prepare($sql);

    //Auncun bind à faire, on execute la requête
    $statement->execute();

    //On récupère grâce au fetchAll() tous les produits stockés en BDD
    $produits = $statement->fetchAll(PDO::FETCH_ASSOC);

    // debug($statement,2);

    // debug($produits,1);



require_once('../../incs/header.php');
require_once('../incs/back_nav.php');
?>




        <div class="container">

                <h1 class="text-center my-5">Listes des produits</h1>

                    <h3 class="text-center my-3"> nombre de produits en base de données <span class="badge bg-info"><?= $statement->rowCount() ?> </span></h3>

                    <?php 

                        if(isset($_SESSION['success_produit'])) echo $_SESSION['success_produit'];
                        if(isset($_SESSION['erreur_produit'])) echo $_SESSION['erreur_produit'];
                        unset($_SESSION['erreur_produit']);
                        unset($_SESSION['success_produit']);

                      ?>
                    <div class="text-center">  
                        <a href="<?php URL ?>add_produit.php?>" class="btn btn-primary mx-auto my-2">Ajouter un nouveau produit</a>
                    </div>   
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">    
                    <?php foreach($produits as $produit): ?>
                    <div class="col">
                        <div class="card my-font-size">
                             <img style="max-height: 20vh; object-fit:contain;" src="<?= $produit['photo'] ?>" class="card-img-top img-fluid p-2" alt="...">
                                <div class="card-body text-center text-dark fst-bold" >
                                    <h5 class="card-title">Titre: <?= $produit['titre'] ?></h5>
                                    <hr>
                                    <p style='overflow:hidden; max-height:80px;' class="card-text"><?= substr($produit['description'], 0, 100) . '...' ?></p>
                                    <hr>
                                    <p class="card-text ">Catégorie: <?php echo $produit['categorie'] ?> - Référence: <?= $produit['reference'] ?></p>
                                    <p class="card-text text-center">id_produit : <?= $produit['id_produit'] ?> - Prix: <?= $produit['prix'] ?>  - Stock: <?= $produit['stock'] ?></p>
                                    <p class="card-text">Public: <?php echo $produit['public'] ?> - Taille: <?php echo $produit['taille'] ?> - Couleur:  <?php echo $produit['couleur'] ?></p>
                                    <div class="nav-link text-center">
                                    <a href="<?php URL ?>show_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm btn-info"><i class="fa-solid fa-eye"></i></a>
                                    <a href="<?php URL ?>edit_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm  btn-warning"><i class="fa-solid fa-pen"></i></a>
                                    <a onclick="return(confirm('Etes-vous certain de vouloir supprimer définitivement ce produit?'))" href="<?php URL ?>delete_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm  btn-danger"><i class="fa-solid fa-trash-can"></i></a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <!-- fin boucle -->
                </div>
        </div>



<?php
require_once('../incs/back_footer.php');

