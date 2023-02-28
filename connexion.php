<?php
require_once('incs/init.php');

//Si la personne est connecté, redirection
if(connect())
{
    header('location: profile.php');
}



//Vérifier les données récupérées en POST

if ($_POST) {
    $border = "border border-danger";
    if (isset($_POST['email'], $_POST['password'])) {

        //On extrait les données renseignées dans les champs, sous forme de variables du même nom
        extract($_POST);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorEmail = "<small class='text-danger text-italic'> Merci de respecter le bon format ex: junior@yahoo.fr</small>";
        } elseif (empty($email)) {
            $errorEmail = "<small class='text-danger text-italic'> Veuillez renseigner un email</small>";
        } else {
            //On peut envoyé la requête de selection, afin de vérifier si l'email est concordant
            $sqlEmail = "SELECT * FROM membre WHERE email = :email";
            $verifEmail = $pdo->prepare($sqlEmail);
            $verifEmail->bindValue(":email", $email, PDO::PARAM_STR);
            $verifEmail->execute();

            if ($verifEmail->rowCount() === 1) {
                //On récupére toutes les données de utilisateur sous forme de tab ASSOC
                $user = $verifEmail->fetch(PDO::FETCH_ASSOC);
                // debug($user,1);
                //Nous pouvons désormais comparer si le mot de passe saisi est correcte
                if (password_verify($password, $user['password'])) {
                    //Si toutes les infos de connexion sont bonnes on peut créer un tableau multidimensionnel dans la $_SESSION pour y sauvergarder les infos de la page profil
                    foreach ($user as $key => $value) {
                        if ($key !== 'password') {
                            $_SESSION['membre'][$key] = $value;
                        }
                    }

                    header("location: profile.php");
                } else {
                    //On stock un msg d'erreur
                    $errorPassword = "<small class='text-danger text-italic'>Votre mot de passe est incorrecte</small>";
                }
            } else {
                //On stock un msg d'erreur
                $errorEmail = "<small class='text-danger text-italic'> Votre identifiant est incorrecte</small>";
            }
        }
    }
}


require_once('incs/header.php');
require_once('incs/nav.php');
?>

 <?php if (isset($_SESSION['success_inscription'])) {
     echo  $_SESSION['success_inscription'];
 }
                      unset($_SESSION['success_inscription']);
?>


    <div class="container">

            <h1 class="text-center my-5">Identifiez-vous</h1>

                <div class="mx-auto col-4 col-lg-6 text-center rounded shadow-lg p-2 bg-light">
                <form method="POST" class="col-4 col-lg-6 mx-auto text-center my-3">
                    <div class="form-group mb-3">
                       <label for="email" class="form-label">Email</label>
                       <input type="text" class="form-control <?php if(isset($errorEmail)) echo $border ?>" id="email" name="email"> 
                       <?php if(isset($errorEmail)) echo $errorEmail ?>
                    </div>
                     <div class="form-group mb-3">
                       <label for="password" class="form-label">Mot de passe</label>
                       <input type="text" class="form-control  <?php if(isset($errorPassword)) echo $border?>" id="password" name="password"> 
                        <?php if(isset($errorPassword)) echo $errorPassword ?>
                    </div>
                    <div class="form-group mb-3">
                        <p class="text-center text-primary"> <a class='text-info nav-link fst-italic' href="">Mot de passe oublié?</a> </p>
                    </div>
                        <button type="submit" class="btn btn-primary mx-auto">Connexion</button>
                </form>
            </div>    
    </div>



<?php
require_once('incs/footer.php');
