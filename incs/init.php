<?php
//CONNEXION A LA BASE DE DONNEES
define("DRIVER", "mysql");
define("HOST", "localhost");
define("DATABASE", "ecommerce");
define("USERNAME", "root");
define("PASSWORD", "");

try{
    //On tente une connexion vers la base de données
    $pdo = new PDO(DRIVER .':'. 'host=' .  HOST . ';dbname=' . DATABASE , USERNAME,  PASSWORD ,[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8"']
);
}catch(PDOException $e){
    //Si jamais on rencontre une erreur, elle est récupérée dans le bloc catch
    echo "Erreur : " . $e->getMessage();//On retourne un echo du message d'erreur
    die();
}


//On démarre
session_start();

//Protection contre les FAILLES XSS

//$_POST
foreach($_POST as $key => $value)
{
    //On boucle sur toutes les données transmises par l'utilisateur en POST
    //Ensuite on sécurise les données grâce à la fonction htmlspecialchars() => les balises HTML perdent leurs "pouvoirs"
    $_POST[$key] = htmlspecialchars($value);
    //On echappe les espaces en début et en fin de chaine
    $_POST[$key] = trim($value);
}

//$_GET
foreach($_POST as $key => $value)
{
    //On boucle sur toutes les données transmises par l'utilisateur via l'URL $_GET
    //Ensuite on sécurise les données grâce à la fonction htmlspecialchars() => les balises HTML perdent leurs "pouvoirs"
    $_POST[$key] = htmlspecialchars($value);
    //On echappe les espaces en début et en fin de chaine
    $_POST[$key] = trim($value);
}




//DEFINIR DES CONSTANTES UTILES
define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . "/E-commerce/");
//Cette constante retourne le chemin physique du dossier contenant notre site web sur le serveur XAMPP
// echo RACINE_SITE; 
define("URL", "http://localhost/E-commerce/");

require_once(RACINE_SITE . "incs/fonctions.php");