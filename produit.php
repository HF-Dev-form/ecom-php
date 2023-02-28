<?php
require_once('incs/init.php');
   
   

    //On tock la requête
    $sql = "SELECT * FROM produit WHERE stock > 0";

    //on prépare la requête, cela instancie au passage un PDOStatement
    $statement = $pdo->prepare($sql);

    //Auncun bind à faire, on execute la requête
    $statement->execute();

    //On récupère grâce au fetchAll() tous les produits stockés en BDD
    $produits = $statement->fetchAll(PDO::FETCH_ASSOC);

    // debug($statement,2);

    // debug($produits,1);


    

require_once('incs/header.php');
require_once('incs/nav.php');
?>


  


        <div class="container">

                <h1 class="text-center my-5">Produits disponible</h1>


                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3">    
                    <?php foreach($produits as $produit): ?>
                    <div class="col">
                        <div class="card my-font-size">
                            <a href="<?php URL ?>detail_produit.php?id=<?= $produit['id_produit'] ?>"> <img style="max-height: 20vh; object-fit:contain;" src="<?= $produit['photo'] ?>" class="card-img-top img-fluid p-2" alt="..."></a>
                                <div class="card-body text-center text-dark fst-bold" >
                                    <h5 class="card-title">Titre: <?= $produit['titre'] ?></h5>
                                    <hr>
                                    <p style='' class="card-text"><a class="nav-link" href="<?php URL ?>detail_produit.php?id=<?= $produit['id_produit'] ?>"> <?= substr($produit['description'], 0, 100) . '...' ?> </a></p>
                                    <hr>
                                    <p class="card-text ">Catégorie: <?php echo $produit['categorie'] ?> - Référence: <?= $produit['reference'] ?></p>
                                    <p class="card-text text-center"> Prix: <?= $produit['prix'] ?>  - Stock: <?= $produit['stock'] ?></p>
                                    <p class="card-text">Public: <?php echo $produit['public'] ?> - Taille: <?php echo $produit['taille'] ?> - Couleur:  <?php echo $produit['couleur'] ?></p>
                                    <div class="nav-link">
                                    <a href="<?php URL ?>detail_produit.php?id=<?= $produit['id_produit'] ?>" class="btn btn-sm btn-info mb-1">Voir en détail</i></a>
                                    <a href="<?php URL ?>panier.php?id=<?= $produit['id_produit'] ?>&action=add" class="btn btn-sm btn-success mb-1"> <i class="fa-solid fa-cart-arrow-down"></i></a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <!-- fin boucle -->
                </div>
        </div>



<?php
require_once('incs/footer.php');

