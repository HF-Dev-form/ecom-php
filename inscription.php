<?php
require_once("incs/init.php");


//1 Contrôler en PHP sue l'on receptionne bien toutes les saisies de l'utilisateurs
// debug($_POST,1);

if ($_POST) {
    //On stock une classe bootstrap en cas d'erreur
    $border = "border border-danger";

    $pregCp = "/^(F-)?((2[A|B])|[0-9]{2})[0-9]{3}$/";


    //On vérifie que tous les champs du formulaire on étaient définis dans $_POST
    if (isset($_POST['civilite'], $_POST['nom'], $_POST['prenom'], $_POST['pseudo'],$_POST['email'],$_POST['password'],$_POST['confirm_pass'], $_POST['adresse'], $_POST['ville'], $_POST['code_postal'])) {

        // extract() : fonction prédéfine PHP permettant d'extraire tous les indexes contenu dans $_POST dans des variables portant le même nom ($civilite, $nom, $prenom, $pseudo, etc)
        extract($_POST);

        //On fait une requête de selection en bdd sur la table membre
        $sqlEmail = "SELECT * FROM membre WHERE email = :email";

        $verifEmail = $pdo->prepare($sqlEmail);
        $verifEmail->bindValue(":email", $email, PDO::PARAM_STR);
        $verifEmail->execute();

        $sqlPseudo = "SELECT * FROM membre WHERE pseudo = :pseudo";

        $verifPseudo = $pdo->prepare($sqlPseudo);
        $verifPseudo->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
        $verifPseudo->execute();

        //Ensuite nous testons si les variables extraites ci-dessus, contiennent les valeurs attendues
        if (empty($nom)) {
            //Sinon on stock un message d'erreur, afin de pour l'afficher dans le formulaire
            $errorNom = "<small class='text-danger text-italic'>Merci de renseigner un nom </small>";
            $error = true;
        }
        //Nous pouvons également ajouter d'autres paramètres à tester (longueur, regEX, etc)
        elseif (strlen($nom) < 2 || strlen($nom) > 50) {
            $errorNom = "<small class='text-danger text-italic'> Votre nom doit être comprit entre 2 et 50 caractères </small>";
            $error = true;
        }

        if (empty($prenom)) {
            $errorPrenom = "<small class='text-danger text-italic'>Merci de renseigner un prenom </small>";
            $error = true;
        } elseif (strlen($prenom) < 2 || strlen($prenom) > 50) {
            $errorPrenom = "<small class='text-danger text-italic'> Votre prénom doit être comprit entre 2 et 50 caractères </small>";
            $error = true;
        }

        //Vérifier que le champs de l'adresse et ville sont bien rempli
        if (empty($adresse)) {
            $errorAdresse = "<small class='text-danger text-italic'> Veuillez renseigner une adresse </small>";
            $error = true;
        }
        if (empty($ville)) {
            $errorVille = "<small class='text-danger text-italic'> Veuillez renseigner une ville </small>";
            $error = true;
        }


        //S'assurer que le champs code postal soit bien de type numérique et qu'il soit egal à 5 caractères
        if (empty($code_postal)) {
            $errorCp = "<small class='text-danger text-italic'> Veuillez renseigner un code postal ex: 75010</small>";
            $error = true;
        }
        if (!preg_match($pregCp, $code_postal)) {
            $errorCp = "<small class='text-danger text-italic'> Merci de respecter le bon format ex: 75010</small>";
            $error = true;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorEmail = "<small class='text-danger text-italic'> Merci de respecter le bon format ex: junior@yahoo.fr</small>";
            $error = true;
        } elseif (empty($email)) {
            $errorEmail = "<small class='text-danger text-italic'> Veuillez renseigner un email</small>";
            $error = true;
        } elseif ($verifEmail->rowCount() > 0) {
            $errorEmail = "<small class='text-danger text-italic'>Cette email est indisponible, merci d'en saisir un nouveau</small>";
            $error = true;
        }

        if (empty($pseudo)) {
            $errorPseudo = "<small class='text-danger text-italic'>Veuillez saisir un pseudo</small>";
            $error = true;
        } elseif ($verifPseudo->rowCount() > 0) {
            $errorPseudo = "<small class='text-danger text-italic'>Ce pseudo est indiponible, veuillez en choisir un autre</small>";
            $error = true;
        } elseif (strlen($pseudo) < 2 || strlen($pseudo) > 30) {
            $errorPseudo = "<small class='text-danger text-italic'>Votre pseudo doit être comprit entre 2 et 30 caractères maximum</small>";
            $error = true;
        }

        if (empty($password)) {
            $errorPassword = "<small class='text-danger text-italic'>Veuillez saisir un mot de passe</small>";
            $error = true;
        } elseif ($password != $confirm_pass) {
            $errorPassword = "<small class='text-danger text-italic'>Les mots de passes sont invalides</small>";
            $error = true;
        }

        if (empty($civilite)) {
            $errorCivilite = "<small class='text-danger text-italic'>Veuillez selectionner une civilité</small>";
            $error = true;
        }

        if (!isset($_POST['pdc'])) {
            $errorPdc = "<small class='text-danger text-italic text-center mx-auto'>Veuillez accepter les politiques de confidentialité</small>";
            $error = true;
        }

        if (!isset($error)) {
            //TODO

            //Si le formulaire est correctement rempli, on peut donc faire le traitement d'insertion en BDD (CREATE)

            //Etant donnée que l'on reçoit un mot de passe (donnée privé), on ne peut le sauvergarder en clair

            //Hashage du mot passe

            $password = password_hash($password, PASSWORD_ARGON2I);

            //Requête d'insertion

            $sqlInsert = "INSERT INTO membre (civilite, nom, prenom, pseudo, email, password, adresse, ville, code_postal) VALUES
                          (:civilite, :nom, :prenom, :pseudo, :email, :password, :adresse, :ville, :code_postal)";

            //On prépare la requête avec pdo
            $insertUser = $pdo->prepare($sqlInsert);

            //On bind (lie ensemble) les valeurs récupérées dans le formulaire avec les marqueurs nominatifs
            $insertUser->bindValue(":civilite", $civilite, PDO::PARAM_STR);
            $insertUser->bindValue(":nom", $nom, PDO::PARAM_STR);
            $insertUser->bindValue(":prenom", $prenom, PDO::PARAM_STR);
            $insertUser->bindValue(":pseudo", $pseudo, PDO::PARAM_STR);
            $insertUser->bindValue(":email", $email, PDO::PARAM_STR);
            $insertUser->bindValue(":password", $password, PDO::PARAM_STR);
            $insertUser->bindValue(":adresse", $adresse, PDO::PARAM_STR);
            $insertUser->bindValue(":ville", $ville, PDO::PARAM_STR);
            $insertUser->bindValue(":code_postal", $code_postal, PDO::PARAM_STR);

            //Execution de la requête préparée
            $insertUser->execute();

            //Message contenu dans la session lors de la redirection

            $_SESSION['success_inscription'] = '
            <div style="background-color:lawGreen; color: white;" class="alert alert-dismissible fade show col-5 text-center mx-auto my-2 fst-bold" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Félicitations votre compte est créer, vous pouvez dès à présent vous connecter</strong> 
            </div>
            ';

            //Après succès, on redirige l'utilisateur vers la page de connexion
            header("location: connexion.php");
        } else {
            $_SESSION['erreur_inscription'] = '
            <div style="background-color:crimson; color: white;" class="alert alert-dismissible fade show col-5 text-center mx-auto my-2 fst-bold" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>Le formulaire est incomplet, merci de bien vouloir saisir vos informations </strong> 
            </div>
            ';
        }
    }
}

require_once("incs/header.php");
require_once("incs/nav.php");
?>


    <div class="container">
        <h1 class="text-center my-5">Créer votre compte</h1>

                <?php if (isset($_SESSION['erreur_inscription'])) {
                    echo  $_SESSION['erreur_inscription'];
                }
                      unset($_SESSION['erreur_inscription']);
?>

            <form method="post" class="row g-3 mb-5 col-md-8 col-lg-10 col-xl-10 mx-auto">
            <?php if (isset($errorPdc)) {
                echo $errorPdc;
            }?>
                    <div class="col-6">
                        <label for="civilite" class="form-label">Civilité</label>
                        <select class="form-select <?php if (isset($errorCivilite)) {
                            echo $border;
                        } ?>" id="civilite" name="civilite">
                            <option value="" aria-readonly="">--Selectionner une civilité--</option>    
                            <option value="homme">Monsieur</option>
                            <option value="femme">Madame</option>
                            <option value="autre">Autre</option>
                        </select>
                        <?php if (isset($errorCivilite)) {
                            echo $errorCivilite;
                        } ?>
                    </div>
                    <div class="col-md-6">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control <?php if (isset($errorNom)) {
                            echo $border;
                        } ?>" id="nom" name="nom" placeholder="Saisir votre nom">
                         <?php if (isset($errorNom)) {
                             echo $errorNom;
                         }?>
                    </div>
                     <div class="col-6">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control <?php if (isset($errorPrenom)) {
                            echo $border;
                        }?>" id="prenom" name="prenom" placeholder="Saisir votre prénom">
                        <?php if (isset($errorPrenom)) {
                            echo $errorPrenom;
                        }?>
                    </div>
                    <div class="col-md-6">
                        <label for="pseudo" class="form-label">Nom d'utilisateur</label>
                        <input type="text" class="form-control <?php if (isset($errorPseudo)) {
                            echo $border;
                        }?>" id="pseudo" name="pseudo" value="">
                        <?php if (isset($errorPseudo)) {
                            echo $errorPseudo;
                        }?>
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Mot de passe <i class="fas fa-eye-slash text-primary" id="showPassword" title="cliquez pour afficher le mot de passe"></i></label>
                        <input type="password" class="form-control <?php if (isset($errorPassword)) {
                            echo $border;
                        }?>" id="password" name="password">
                        <?php if (isset($errorPassword)) {
                            echo $errorPassword;
                        }?>
                    </div>
                    <div class="col-md-6">
                        <label for="confirm_password" class="form-label">Confirmer votre mot de passe</label>
                        <input type="password" class="form-control <?php if (isset($errorPassword)) {
                            echo $border;
                        }?>" id="confirm_pass" name="confirm_pass">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control <?php if (isset($errorEmail)) {
                            echo $border;
                        }?>" id="email" name="email" placeholder="Saisir votre adresse email" value="">
                        <?php if (isset($errorEmail)) {
                            echo $errorEmail;
                        }?>
                    </div>
                    <div class="col-md-6">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control <?php if (isset($errorAdresse)) {
                            echo $border;
                        }?>" id="adresse" name="adresse" placeholder="Saisir votre adresse">
                        <?php if (isset($errorAdresse)) {
                            echo $errorAdresse;
                        } ?>
                    </div>
                    <div class="col-md-4">
                        <label for="ville" class="form-label">Ville</label>
                        <input type="text" class="form-control <?php if (isset($errorVille)) {
                            echo $border;
                        }?>" id="ville" name="ville" placeholder="Saisir votre ville">
                        <?php if (isset($errorVille)) {
                            echo $ville;
                        }?>
                    </div>
                    <div class="col-md-2">
                        <label for="code_postal" class="form-label">Code postal</label>
                        <input type="text" class="form-control" id="code_postal" name="code_postal">
                            <?php if (isset($errorCp)) {
                                echo $errorCp;
                            } ?>
                    </div>
                    <div class="col-12">
                        <div class="text-center">
                            <input class="form-check-input" type="checkbox" id="pdc" name="pdc" value="checked">
                            <label class="form-check-label" for="pdc">
                            Accepter les <a href="" class="alert-link text-dark">politiques de confidentialité</a>  
                            </label>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-dark">Continuer</button>
                   </div>
            </form>
    </div>


<?php
                            require_once("incs/footer.php");
