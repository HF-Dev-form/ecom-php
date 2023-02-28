<?php
require_once("incs/init.php");

if(connect())
{
    //Vider l'indexe membre contenu dans la session
    unset($_SESSION['membre']);

    //Redirection vers l'accueil
    header("location: -index.php");

}