<?php 

use Stripe\Stripe;
require_once('incs/init.php');
// CONTROLE STOCK PRODUIT
// Si l'indice 'payer' est bien définit, cela veut que l'internaute a cliqué sur le bouton 'VALIDER LE PAIEMENT' et donc par conséquent que l'attribut name 'payer' a été détecté

// debug($_SESSION,1);
// die();
if (isset($_POST['payer'])) {

    

    // La boucle FOR tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produits dans le panier
    $error = '';
    for ($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++) {
        $r = $pdo->query("SELECT stock FROM produit WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]); 
        $s = $r->fetch(PDO::FETCH_ASSOC);
      

        // SI la quantite du stock du produit en BDD est inférireur à la quantité dans la session, c'est à dire la quantité commandée par l'internaute, alors on entre dans la condition IF
        if ($s['stock'] < $_SESSION['panier']['quantite'][$i]) {
          
            // SI le stock en BDD est supérieur à 0 mais inférieur à la quantité demandée par l'internaute, alors on entre dans la condition IF
            if ($s['stock'] > 0) {
               
                // ON affecte la quantite restante en stock en BDD à la quantité du produit dans le panier dont la quantité demandée est supérieur par rapport au stock restant
                $_SESSION['panier']['quantite'][$i] = $s['stock'];
            } else { // Sinon le stock du produit en BDD est à 0, on entre dans la condition ELSE
              

                supprProduit($_SESSION['panier']['id_produit'][$i]); // on supprime dans la session le produit qui a un stock de 0, en rupture de stock
                $i--; // on fait un tour de boucle en arrière, on décrémente, car array_splice() remonte les indices inférieurs vers les indices supérieur, cela permet de ne pas oublier de contrôler un produit qui aurait remonté d'un indice dans le tableau ARRAY de la session
            }

            $e = true;
        }
    }

     if(!isset($e))
    {
        // ENREGISTREMENT DE LA COMMANDE
        $r = $pdo->exec("INSERT INTO commande (membre_id, montant, date_enregistrement) VALUES (" . $_SESSION['membre']['id_membre'] . ", " . totalPanier() . ", NOW())");

        $idCommande = $pdo->lastInsertId(); // permet de récupérer le dernier id_commande crée dans la BDD afin de l'enregistrer dans la table details_commande, pour chaque produit à la bonne commande

        // La boucle FOR tourne autant de fois qu'il y a d'id_produit dans la session, donc autant qu'il y a de produits dans le panier
       
        for($i = 0; $i < count($_SESSION['panier']['id_produit']); $i++)
        {
            // Pour chaque tour de boucle FOR, on execute une requete d'insertion dans la table details_commande pour chaque produit ajouté
            // On récupère le dernier id_commande généré en BDD afin de relier chaque produit à la bonne commande dans la table details_commande
            $r = $pdo->exec("INSERT INTO details_commande (commande_id, produit_id, quantite, prix) VALUES ($idCommande, " . $_SESSION['panier']['id_produit'][$i] . ", " . $_SESSION['panier']['quantite'][$i] . ", " . $_SESSION['panier']['prix'][$i] . ")");

            // Dépréciation des stocks
            // Modifie la table 'produit' afin que le stock soit égal au stock de la BDD MOINS la quantité du produit commandé A CONDITION que l'id_produit de la BDD soit égal à l'id_produit du produit stocké dans le panier de la session 
            $r = $pdo->exec("UPDATE produit SET stock = stock - " . $_SESSION['panier']['quantite'][$i] . " WHERE id_produit = " . $_SESSION['panier']['id_produit'][$i]);
        }
        unset($_SESSION['panier']); // on supprime les éléments du panier dans la session après la validation du panier et l'insertion dans les tables 'commande' et  'details_commande'
        
        $_SESSION['num_cmd'] = $idCommande;
          require 'vendor/autoload.php';
   
   
    // This is your test secret API key.
  
    header('Content-Type: application/json');

    $YOUR_DOMAIN = URL;

    
            \Stripe\Stripe::setApiKey('sk_test_51KtvjeIXHg8874bq8Te1VqcDs7W7YWlctQeFsJ33VemlRbEqh9lAL0Cm4tpSz6P8GqNMJ2UJZyAxnc8bZc3qJw6L00872pY4to'
);

                  $prod = $pdo->query("SELECT * FROM details_commande ORDER BY commande_id DESC LIMIT 1");

                     $lastcom = $prod->fetch(PDO::FETCH_ASSOC);



                    $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'EUR',
                            'product_data' => [
                                'name' => $lastcom['produit_id']
                            ],
                            'unit_amount' => $lastcom['prix'] * 100
                        ],
                        'quantity' => $lastcom['quantite'],
                    ]],
                    'mode' => 'payment',
                    'success_url' => $YOUR_DOMAIN . 'validation_cmd.php',
                    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
                ]);
     
                header("HTTP/1.1 303 See Other");
                header("Location: " . $checkout_session->url);
    } 
}