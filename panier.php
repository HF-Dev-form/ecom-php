<?php
require_once("incs/init.php");

if (empty($_SESSION["panier"]["id_produit"])) {
    header("location: produit.php");
}


// debug($_POST,1);
if ($_POST) {
    $statement = $pdo->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
    $statement->bindValue(":id_produit", $_POST['id_produit'], PDO::PARAM_STR);
    $statement->execute();

    //On récupère les données correspondantes à l'id_produit
    $produit = $statement->fetch(PDO::FETCH_ASSOC);

    extract($produit);
    // // debug($produit, 1);

    ajoutPanier($id_produit, $reference, $titre, $photo, $prix, $_POST['quantite'], $stock);

    // debug($_SESSION, 1);
}

if ($_GET) {
    if (!empty($_GET['id']) && !empty($_GET['action'])) {
        if ($_GET['action'] == 'add') {
            $id = $_GET['id'];

            $sql = "SELECT * FROM produit WHERE id_produit = :id_produit";
            $statement2 = $pdo->prepare($sql);
            $statement2->bindValue(":id_produit", $id, PDO::PARAM_STR);
            $statement2->execute();

            $produitIncrement = $statement2->fetch(PDO::FETCH_ASSOC);

            extract($produitIncrement);

            $indexProduit = array_search($id, $_SESSION['panier']['id_produit']);

            if ($indexProduit !== false) {
                increment($id, $stock);
            } else {
                ajoutPanier($id_produit, $reference,$titre, $photo, $prix, 1, $stock);
            }
        }

        if ($_GET['action'] == 'decrement') {
            $id = $_GET['id'];
            decrement($id);
        }



        // if ($_GET['action'] == 'add_principal') {
        //     $id = $_GET['id'];

        //     $sql = "SELECT * FROM produit WHERE id_produit = :id_produit";
        //     $statement2 = $pdo->prepare($sql);
        //     $statement2->bindValue(":id_produit", $id, PDO::PARAM_STR);
        //     $statement2->execute();

        //     $produitIncrement = $statement2->fetch(PDO::FETCH_ASSOC);

        //     extract($produitIncrement);




        //     if (isset($_SESSION['panier'])) {
        //         panier();
        //         increment($id, $stock);
        //     }

        //     header("location: produit.php");
        // }

        if ($_GET['action'] == 'destroy') {
            $id = $_GET['id'];
            supprProduit($id);

            $_SESSION['supp_panier'] = '<div style="background-color:lightGreen; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         <strong>Le produit a bien été supprimé</strong>
                        </div>';

            header("location: " . $_SERVER['REFERER']);
        }
    }
}






require_once('incs/header.php');
require_once('incs/nav.php');

?>

    <div class="container">

        <h1 class="text-center my-5">Votre panier</h1>

        <?php if (isset($_SESSION['supp_panier'])) {
            echo $_SESSION['supp_panier'];
        }
            unset($_SESSION['supp_panier']);

//  session_destroy();
?>

        <table class="table table-bordered mx-auto">
            <thead>
                <tr class="text-center">
                    <th>PHOTO</th>
                    <th>REFERENCE</th>
                    <th>TITRE</th>
                    <th>PRIX UNIT</th>
                    <th>PRIX TOTAL/PRODUIT</th>
                    <th>SUPP</th>    
                </tr>
            </thead>
            <tbody>
             <?php if (empty($_SESSION['panier']['id_produit'])): ?>

                <tr class="text-center">
                    <td class="text-danger" colspan="6">Votre panier est vide</td>
                </tr>
                <?php else: ?>
                <?php for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++): ?>
                <?php if(isset($i)): ?>
                <tr class="text-center">
                    <td><img style="max-width: 30%; max-height: 20vh;" class="img-fluid" src="<?= $_SESSION['panier']['photo'][$i] ?>" alt=""></td>
                     <td><?= $_SESSION['panier']['reference'][$i] ?></td>
                     <td><?= $_SESSION['panier']['titre'][$i] ?></td>
                     <td><small><?= $_SESSION['panier']['prix'][$i] ?>€ x  <?= $_SESSION['panier']['quantite'][$i] ?> </small><br>
                        <?php if ($_SESSION['panier']['quantite'][$i] < $_SESSION['panier']['stock'][$i]): ?>
                        <small> 
                            <a class="btn btn-sm btn-info" href="panier.php?id=<?= $_SESSION['panier']['id_produit'][$i]?>&action=add">+</a>
                        </small>
                        <?php endif ?> 
                        
                        <small>
                             <a class="btn btn-sm btn-warning" href="panier.php?id=<?= $_SESSION['panier']['id_produit'][$i] ?>&action=decrement">-</a>
                        </small> 
                    
                     </td>
                     <td class="text-success"><?= ($_SESSION['panier']['prix'][$i] * $_SESSION['panier']['quantite'][$i])?>€</td>
                     <?php endif ?>
                     <td><a onclick="return(confirm('Etes-vous certain de vouloir supprimer ce produit?'))" href="panier.php?id=<?= $_SESSION['panier']['id_produit'][$i] ?>&action=destroy" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash-can"></i></a></td>  
                </tr>
                <?php endfor ?>   
                
        <?php endif ?>     
            </tbody>
            <tfoot>
                    <th class="text-center bg-dark text-white"> MONTANT TOTAL</th>
                    <td class="text-end" colspan="6"><h4> <?php echo totalPanier()?> € </h4></td>
            </tfoot>  
        </table>

         <?php if (connect() && totalPanier() > 0): ?>

         <div class="text-end">
            <form action="commande.php" method="post" class="col-md-12 mx-auto mb-3">
                <input type="submit" name="payer" value="VALIDER LE PAIEMENT" class="btn btn-success ">
            </form>

            <?php elseif (!connect() && totalPanier() > 0): ?>
                <div class="text-end">
                     <a href="<?= URL ?>connexion.php" class="btn btn-info">IDENTIFIEZ-VOUS POUR VALIDER LA COMMANDE</a>
                </div>
               <?php else: ?>
                 <div class="text-end">
                     <a href="<?= URL ?>produit.php" class="btn btn-info">AJOUTER DES PRODUIT A VOTRE PANIER</a>
                </div>
         </div>
         <?php endif; ?>

    </div>



<?php
require_once('incs/footer.php');
