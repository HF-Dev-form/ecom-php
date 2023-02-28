<?php

use Stripe\Stripe;
use Stripe\Checkout\Session;


//FONCTION DEBOGGAGE
function debug($var, int $mod)
{
    if($mod === 1){
     echo '<pre class="bg-warning text-center fst-bold">';
        print_r($var);
     echo '</pre>';

    }else{
         echo '<pre class="bg-warning text-center fst-bold">';
          var_dump($var);
         echo '</pre>';
    }
}

//ficher actuel
function pageActive($pagePath)
{
    
    if($pagePath === substr($_SERVER['REQUEST_URI'],12))
    {
       echo 'active';
    }
    else
    {
        echo '';
    }

}

//Fonction user connecté
function connect()
{
    if(isset($_SESSION['membre']))
    {
        //Si lors de la connexion l'utilisateur
        return true;
    }
    else
    {
        return false;
    }
}


//Fonction user authentifié ADMIN
function is_admin()
{
    //Si l'indice 'membre' dans la session est définit, cela signifie que l'indice 'statut' existe en session
    if(connect() && $_SESSION['membre']['statut'] === 'admin')
    {
        return true;
    }
    else
    {
        //Son statut n'est pas admin
        return false;
    }
}

//CREATION DU PANIER EN SESSION
function panier()
{
    //Si l'indice panier dans la session n'est pas définit cela veut dire que l'utilisateur n'a pas encore ajouté de produit dans le panier, alors on peut créer le panier dans la session de l'utilisateur
    if(!isset($_SESSION['panier']))
    {
        $_SESSION['panier'] = [];
        $_SESSION['panier']['id_produit'] = [];
        $_SESSION['panier']['reference'] = [];
        $_SESSION['panier']['photo'] = [];
        $_SESSION['panier']['titre'] = [];
        $_SESSION['panier']['prix'] = [];
        $_SESSION['panier']['quantite'] = [];
        $_SESSION['panier']['stock'] = [];
       
    }
}

//FONCTION PERMETTANT D'AJOUTER UN PRODUIT DANS LE PANIER
//Les paramètres (arguments) passés dans la fonction permettront de receptionner les informations du produit ajoutés
//Nous pourrons ensuite les stocker dans les différents champs du tableau panier
function ajoutPanier($id_produit, $reference, $titre, $photo, $prix, $quantite, $stock)
{
    panier();

    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)
    {
        $_SESSION['panier']['quantite'][$positionProduit] += $quantite;
    }
    else
    {
        $_SESSION['panier']['id_produit'][] = $id_produit;
        $_SESSION['panier']['reference'][] = $reference;
        $_SESSION['panier']['photo'][] = $photo;
        $_SESSION['panier']['titre'][] = $titre;
        $_SESSION['panier']['prix'][] = $prix;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['stock'][] = $stock;
    }
}


//FONCTION POUR AVOIR LE MONTANT TOTAL DU PANIER
function totalPanier()
{
    $total = 0;
    foreach ($_SESSION["panier"]["id_produit"] as $id)
    {
        $i = array_search($id, $_SESSION['panier']['id_produit']);
        if (isset($_SESSION["panier"]["id_produit"][$i]))
        $total += $_SESSION["panier"]["prix"][$i] * $_SESSION["panier"]["quantite"][$i];
    }
    return $total;
}


//Fonction pour incrément (de 1 en 1)
function increment($id_produit, $stockProduit)
{
    //Est-ce qu'un panier existe?
    panier();

     $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)
    {
        if($_SESSION['panier']['quantite'][$positionProduit] < $stockProduit)
          $_SESSION['panier']['quantite'][$positionProduit] += 1;
         
    }
  
}

//Fonction decrement (retirer un produit de 1 en 1)
function decrement($id_produit)
{
     panier();

     $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)
    {
        if($_SESSION['panier']['quantite'][$positionProduit]  > 1)
        {
            $_SESSION['panier']['quantite'][$positionProduit] -= 1;
        }
        else
        {
            unset($_SESSION['panier']['id_produit'][$positionProduit]);
            unset($_SESSION['panier']['reference'][$positionProduit]);
            unset($_SESSION['panier']['photo'][$positionProduit]);
            unset($_SESSION['panier']['titre'][$positionProduit]);
            unset($_SESSION['panier']['prix'][$positionProduit]);
            unset($_SESSION['panier']['quantite'][$positionProduit]);
            unset($_SESSION['panier']['stock'][$positionProduit]);

           debug($_SESSION, 1);
        }
          
         
    }
}

//Fonction pour supprimer un produit du panier
function supprProduit($id_produit)
{

    $positionProduit = array_search($id_produit, $_SESSION['panier']['id_produit']);

    if($positionProduit !== false)
    {

            unset($_SESSION['panier']['id_produit'][$positionProduit]);
            unset($_SESSION['panier']['reference'][$positionProduit]);
            unset($_SESSION['panier']['photo'][$positionProduit]);
            unset($_SESSION['panier']['titre'][$positionProduit]);
            unset($_SESSION['panier']['prix'][$positionProduit]);
            unset($_SESSION['panier']['quantite'][$positionProduit]);
            unset($_SESSION['panier']['stock'][$positionProduit]);
        
    }

}

//Fonction destruction panier
function destructionPanier()
 {
    if(isset($_SESSION['panier']))
    {
        unset($_SESSION['panier']);
    }
 }

 //Fonction   
 function stripe()
 {
    require 'vendor/autoload.php';
    // This is your test secret API key.
    Stripe::setApiKey('sk_test_51KtvjeIXHg8874bq8Te1VqcDs7W7YWlctQeFsJ33VemlRbEqh9lAL0Cm4tpSz6P8GqNMJ2UJZyAxnc8bZc3qJw6L00872pY4to');

    header('Content-Type: application/json');

    $YOUR_DOMAIN = URL;

    
            \Stripe\Stripe::setApiKey('sk_test_51KtvjeIXHg8874bq8Te1VqcDs7W7YWlctQeFsJ33VemlRbEqh9lAL0Cm4tpSz6P8GqNMJ2UJZyAxnc8bZc3qJw6L00872pY4to');

                    $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'EUR',
                            'product_data' => [
                                'name' => 'toto'
                            ],
                            'unit_amount' => 100000
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => $YOUR_DOMAIN . 'validation_cmd.php',
                    'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
                ]);
     
                header("HTTP/1.1 303 See Other");
                header("Location: " . $checkout_session->url);

 }