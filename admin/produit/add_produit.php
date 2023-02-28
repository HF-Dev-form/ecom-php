<?php
require_once('../../incs/init.php');

    if(!is_admin())
    {
        header("location:" . URL .  "-index.php");
    }

    //On vérifie qu'il y ai bien des données transférés via le formulaire method=POST
    if($_POST)
    {

        //extraction des donnée saisie dans le formulaire via $_POST, on récupère tous les champs du formulaires (l'attribut name de chaque input)
        extract($_POST);   

        // debug($_POST,1);

        // debug($_FILES,1);

        //On vérifie que tous les champs sont bien remplis (toutes les colonnes obligatoires en BDD, donc sauf photo car défini en NULL)
        //Pour ce faire , on teste tous les champs avec la fonction PHP empty() (equivaut à: est-ce vide?)
        if(empty($_POST['reference']) || empty($_POST['categorie']) || empty($_POST['titre']) 
        || empty($_POST['description']) || empty($_POST['taille'])
        || empty($_POST['couleur']) || empty($_POST['public']) 
        || empty($_POST['prix']) || empty($_POST['stock'])
        )
        {
            //Si l'un des champs est vide alors, on entre dans les accolades du IF
            //On stock une div alert contenant un msg d'erreur
            $errorForm = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <strong>Le formulaire est incomplet, merci de bien vouloir remplir tous les champs</strong>
           </div>';
        }
        else
        {
            //Si tous les champs sont rempli =>

            //On prépare une photo en cas de non disponibilité d'image de produit
             $photoIndispo = URL . "uploads/produit/visuel-indisponible-650.png";    

           
            //Traitement et enregistrement de l'image du produit
            if(!empty($_FILES['photo']['name']))
            {
                //on renomme la photo: on remplace les eventuels espaces par un '-', puis on concatène avec une id unique et enfin, concaténation avec le nom original de l'image
                $photoName = str_replace(' ', '-', $reference . '-' . uniqid() . '-' . $_FILES['photo']['name']);
                //On remplace les eventuels slash par '-' et on stock le nom final de la photo dans '$newPhotoName
                $newPhotoName = str_replace('/', '-', $photoName);

               // echo $newPhotoName;

                //URL de l'image stockée en BDD, pour l'affichage de l'image sur le site web
                //http://localhost/E-commerce/updloads/ref-aaa-2121212121212-tishirt.jpg
                $photoDB = URL . "uploads/produit/$newPhotoName";

                //Chemin physique de l'image (à la racine du projet), pour la futur copie de la photo dans le dossier uploads/produit
                $photoDIR = RACINE_SITE . "uploads/produit/$newPhotoName";


                //Traitement pour le contrôle des extensions acceptés
                //on récupere l'extension de l'image uploadé (jpg par exemple)
                $extentionPhoto = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
                //On récupère le mime type de la photo  (image/jpeg par exemple)
                $typePhoto = $_FILES['photo']['type'];
                $sizePhoto = $_FILES['photo']['size'];

                // echo $extentionPhoto;

                //On créer un tableau contenant en indice (key) les extentions autorisés et en valeur les mimes types autorisés
                $typeMmimes = [
                    'bmp' => 'image/bmp',
                    'jpeg' => 'image/jpeg',
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png'
                ];

                //S'il n'y a acune correspondace entre l'extension/mimeType de l'image uploadé
                if(!array_key_exists($extentionPhoto, $typeMmimes) || !in_array($typePhoto, $typeMmimes))
                {       
                    //On entre dnas la condition IF
                    //On génère un msg d'erreur
                        $erroType = '<small class="text-danger fst-italic">Type de fichier non accepté, veuillez insérer une image de type [jpg/jpeg/bmb/png]</small>';

                        //Nous laissons à l'utilisateur la possibilité de ne pas insérer d'image
                        //on défini donc variable $error à true seulement dans le cas d'un upload d'image
                        $error = true;
                }
                //On vérifie que la taille du fichier ne dépasse pas les 3MO
                elseif($sizePhoto > (3000*1000))
                {   
                    //Si c'est le cas on génère une erreur
                    $errorSize = '<small class="text-danger fst-italic">taille autorisé 3Mo maximum</small>';
                     //Nous laissons à l'utilisateur la possibilité de ne pas insérer d'image
                    //on défini donc variable $error à true seulement dans le cas d'un upload d'image
                    $error = true;
                }
                else
                {   
                    //Dans le cas ou l'upload d'image passe tous les barrages (condition IF), cela signifie que l'image répond à nos attentes
                    //On peut doncl' enregistré sur le serveur local  (uploads/produit)
                     move_uploaded_file($_FILES['photo']['tmp_name'], $photoDIR);
                    
                }
  
               
            }

             //Si error est définie, c'est que l'utilisateur a tenté d'uploader une image et qu'elle n'était pas conforme   
             if(!isset($error))
             {
               //S'il y a eu un upload d'image conforme, alors on entre dans la condition IF et préparé l'insertion en BDD
             //On stock la requête d'insertion   
             $sql = "INSERT INTO produit (reference, categorie, titre, description, couleur, taille, photo, public, prix, stock)
                VALUES(:reference, :categorie, :titre, :description, :couleur, :taille, :photo, :public, :prix, :stock)
             ";
             //Préparation de la requête avec PDO
             $insertProduit = $pdo->prepare($sql);
             //On relie chaque saisie récupérée dans le formulaire via $_POST au marqueurs nominatifs défini dans la requ^te
             $insertProduit->bindValue(":reference", $reference, PDO::PARAM_STR);
             $insertProduit->bindValue(":categorie", $categorie, PDO::PARAM_STR);
             $insertProduit->bindValue(":titre", $titre, PDO::PARAM_STR);
             $insertProduit->bindValue(":description", $description, PDO::PARAM_STR);
             $insertProduit->bindValue(":couleur", $couleur, PDO::PARAM_STR);
             $insertProduit->bindValue(":taille", $taille, PDO::PARAM_STR);
             $insertProduit->bindValue(":public", $public, PDO::PARAM_STR);
             $insertProduit->bindValue(":prix", $prix, PDO::PARAM_INT);
             $insertProduit->bindValue(":stock", $stock, PDO::PARAM_INT);

             //S'il n'y a pas eu d'upload d'image   
             if(empty($_FILES['photo']['name']))
             {  
                //On insère l'image que l'on a défini par défaut   
                $insertProduit->bindValue(":photo", $photoIndispo, PDO::PARAM_STR);
             }
             else
             {
                //Sinon insertion de l'image de l'utilisateur
                $insertProduit->bindValue(":photo", $photoDB, PDO::PARAM_STR);
             }

             //Execution de la requête   
             $insertProduit->execute();

             //Message de succès d'ajout du produit, nous retrouverons ce message lors de la redirection dans la page /admin/produit/index.php   
             $_SESSION['success_produit'] =  '<div style="background-color:lightGreen; color:white;" class="alert      alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               <strong>Le formulaire est incomplet, merci de bien vouloir remplir tous les champs</strong>
               </div>';

             header('location: ' . URL . "admin/produit/");
          }
          else
          {     
               //Sinon c'est qu'il y a eu une erreur dans le remplissage du formulaire
               //Alors on stock un message d'erreur
               $errorForm = '<div style="background-color:crimson; color:white;" class="alert alert-dismissible fade show col-md-4 mx-auto text-center fst-bold" role="alert">
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               <strong>Le formulaire est incomplet, merci de bien vouloir remplir tous les champs</strong>
               </div>';
          }

        }

    }

require_once('../../incs/header.php');
require_once('../incs/back_nav.php');
?>

        
        <div class="container">

                <h1 class="text-center my-5">Ajouter un produit</h1>

                    <?php if(!empty($errorForm)) echo $errorForm ?>

                 <form method="POST" enctype="multipart/form-data" class="row g-3 my-5 col-7 mx-auto">
                    <div class="form-group col-md-6">
                        <label for="reference" class="form-label">Référence</label>
                        <input type="text" id="reference" value="<?php if(isset($reference)) echo $reference ?>" name="reference" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="categorie" class="form-label">Catégorie</label>
                        <input type="text" id="categorie" value="<?php if(isset($categorie)) echo $categorie ?>" name="categorie" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="titre" class="form-label">Titre</label>
                        <input type="text" id="titre" value="<?php if(isset($titre)) echo $titre ?>" name="titre" class="form-control">
                    </div>
                    <div class="form-group col-md-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control"> 
                            <?php if(isset($description)) echo $description ?>
                        </textarea>
                    </div>
                    <div class="form-group col-4">
                        <label for="taille" class="form-label">Taille</label>
                        <select name="taille" id="taille" class="form-select">
                            <option value="s"<?php if(isset($taille) && $taille == 's') echo 'selected'?>>S</option>
                            <option value="m"<?php if(isset($taille) && $taille == 'm') echo 'selected'?>>M</option>
                            <option value="l"<?php if(isset($taille) && $taille == 'l') echo 'selected'?>>L</option>
                            <option value="xl"<?php if(isset($taille) && $taille == 'xl') echo 'selected'?>>XL</option>
                        </select>
                    </div>
                     <div class="form-group col-md-4">
                        <label for="couleur" class="form-label">Couleur</label>
                        <input type="text" id="couleur"  value="<?php if(isset($couleur)) echo $couleur ?>" name="couleur" class="form-control">
                    </div>
                     <div class="form-group col-4">
                        <label for="public" class="form-label">Public</label>
                        <select name="public" id="public" class="form-select">
                            <option value="homme"<?php if(isset($taille) && $taille == 'homme') echo 'selected'?>>Homme</option>
                            <option value="femme"<?php if(isset($taille) && $taille == 'femme') echo 'selected'?>>Femme</option>
                            <option value="mixte"<?php if(isset($taille) && $taille == 'mixte') echo 'selected'?>>Mixte</option>
                        </select>
                    </div>
                      <div class="form-group col-md-7">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" id="photo" name="photo" class="form-control">
                        <?php if(isset($erroType)) echo $erroType;
                              if(isset($errorSize)) echo $errorSize;
                         ?>
                     </div>
                        <div class="form-group col-md-5">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="text" id="stock" value="<?php if(isset($stock)) echo $stock ?>" name="stock" class="form-control">
                    </div>
                    <div class="input-group mt-3">
                        <label for="prix" class="">Prix</label>
                        <input type="text" id="prix"value="<?php if(isset($prix)) echo $prix ?>"  name="prix" class="form-control">
                          <div class="input-group-append">
                            <div class="input-group-text">€</div>
                         </div>
                    </div>
                   
                        <div class='text-center'>
                            <button class="btn btn-primary my-1">Ajouter produit</button>
                        </div>
                 </form>   
        </div>
    

<?php
require_once('../incs/back_footer.php');
