<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier votre Profil</title>
    <link rel="stylesheet" href="style.css">
</head>
<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
    if (!isset($_SESSION['login'])) {
        header ('location: connexion.php');
    }
    // si le bouton "update" est appuyé
    else if (isset($_POST['update']))
    {
        //on vérifie si le mdp actuel n'est pas vide
        if (empty($_POST['password']))
        {
            echo 'entrez votre mot de passe';
        }
        //on vérifie si le mdp actuel est bon
        else if (isset($_POST['password']))
        {
            //et on transforme les $post en variable
            $password = $_POST['password'];

            //on se connecte à la base de données:
            $db = mysqli_connect('localhost','root', '', 'moduleconnexion');
            //on fait la requête dans la bd pour rechercher si ces données existent et correspondent:
            $query1 = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE password = '$password'");
            
            // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
            // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
            if(mysqli_num_rows($query1) == 0) {
                echo "le mot de passe actuel est erroné.";
            } 
            //si le mdp est bon on se connecte
            else if ((isset($_POST['login'])) || (isset($_POST['prenom'])) || (isset($_POST['nom'])) && (isset($_POST['password'])) || (isset($_POST['new_pass'])) || (isset($_POST['confirm_new_pass']))) 
            {
                // transformation des $post en variables
                $login = $_POST['login'];
                $prenom = $_POST['prenom'];
                $nom = $_POST['nom'];
                $newpass = $_POST['new_pass'];

                //on vérifie si nouveaux mdp sont identiques par vérifier s'ils sont différents
                if($_POST["new_pass"] != $_POST["confirm_new_pass"] ) {
                    //s'ils sont différents echo
                    echo 'les nouveaux mots de passe sont différents';
                }

                //on vérifie si il y a assez de char
                else if (strlen($login) < 3 || strlen($prenom) < 3 || strlen($nom) < 3 || strlen($newpass) < 3)
                {
                    echo 'pas assez de caractères';
                }

                //on vérifie si l'utilisateur à mis un nouveau login
                else if ($_SESSION['login'] != $login)
                {
                    //on se connecte à la base de données:
                    $db = mysqli_connect('localhost','root', '', 'moduleconnexion');
                    //on fait la requête dans la bd pour rechercher si ces données existent:
                    $query = mysqli_query($db,"SELECT * FROM `utilisateurs` WHERE `login`='$login'");
                    // si il y a un résultat, mysqli_num_rows() nous donnera alors 1
                    // si mysqli_num_rows() retourne 0 c'est qu'il a trouvé aucun résultat
                    if(mysqli_num_rows($query) == 1) 
                    { 
                        echo 'Le login est déjà prit';
                    } 
                }
                else //quand tout va bien
                {
                    //récupération de l'identifiant du user
                    $id = $_SESSION['id'];
                    //on se connecte à la base de données:
                    $db = mysqli_connect('localhost','root', '', 'moduleconnexion');
                    // Requête de modification d'enregistrement dans la bd
                    $query2 = mysqli_query ($db, "UPDATE `utilisateurs` SET `login`= '$login',`prenom`= '$prenom',`nom`= '$nom',`password`= '$newpass' WHERE `id` = '$id'");
                    //on redéfini les session avec les nouvelles informations (si on ne fait pas ça les modificatoins ne seront pas visible sur le form)
                    $_SESSION['login'] = $login;
                    $_SESSION['prenom'] = $prenom;
                    $_SESSION['nom'] = $nom;
                    $_SESSION['password'] = $newpass;
                    $_SESSION['new_pass'] = $newpass;
                    $_SESSION['confirm_new_pass'] = $newpass;

                    //s'assurer que la requ^te a marché, car pas de redirection avec header location
                    if ($query2) {
                        echo 'la modification a été prise en compte';
                    }
                    else {
                        echo 'la modification a échouée';
                    }
                }//fin else quand tout va bien
            }//fin du mot de passe correct
        }// fin isset passaword
    }// fin isset bouton update
?>
<body>
    <!--Cette page possède un formulaire permettant à l’utilisateur de modifier ses
    informations. Ce formulaire est par défaut pré-rempli avec les informations
    qui sont actuellement stockées en base de données.-->

    <!--formulaire de modification des informations du user-->
    <div class="center">
        <h1>Modifier mon Profil</h1>

        <form action="profil.php" method="post">
        
            <div class="txt_field">
                <input type="text" name="login" value="<?php echo ($_SESSION['login']);?>">
                <span></span>
                <label for="login">Login</label> <!--champs login dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="text" name="prenom" value="<?php echo ($_SESSION['prenom']);?>">
                <span></span>
                <label for="prenom">Prénom</label> <!--champs prenom dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="text" name="nom" value="<?php echo ($_SESSION['nom']);?>">
                <span></span>
                <label for="nom">Nom</label> <!--champs nom dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="password" value="<?php echo ($_SESSION['password']);?>">
                <span></span>
                <label for="password">Votre mot de passe actuel</label> <!--champs password dans la table utilisateurs-->
            </div>

            <div class="txt_field">
                <input type="password" name="new_pass" value="<?php echo ($_SESSION['password']);?>">
                <span></span>
                <label for="new_pass">Nouveau mot de passe</label>
            </div>

            <div class="txt_field">
                <input type="password" name="confirm_new_pass" value="<?php echo ($_SESSION['password']);?>">
                <span></span>
                <label for="confirm_new_pass">Confirmez votre nouveau mot de passe</label>
            </div>

            <input type="submit" name="update" value="Mettre à jour"> <!--mon bouton inscription-->

            <div class="signup_link">
                <a href="index.php">Accueil</a>
            </div>

        </form>
    </div>
</body>
</html>
