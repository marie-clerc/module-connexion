<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Votre page</title>
</head>
<body class="admin">
    <!--Cette page est accessible UNIQUEMENT pour l’utilisateur “admin”. Elle
permet de lister l’ensemble des informations des utilisateurs présents dans
la base de données.-->

<?php
    // Initialiser la session
    session_start();
    // Vérifiez si l'utilisateur est connecté, sinon redirige-le vers la page de connexion
    if (!isset($_SESSION['login'])) {
        header ('location: connexion.php');
    }
    //l'utilisateur est connecté, on parcours son login
    else if (isset($_SESSION['login']))
    {
        //tensformation des session en variables
        $login = $_SESSION['login'];
        //on se connecte à la base de données
        $db = mysqli_connect('localhost','root', '', 'moduleconnexion');
        
        //si l'utilisateur connecté EST l'admin alors on liste l’ensemble des informations des utilisateurs présents dans la base de données
        if ($login === 'admin')
        {
            //Requête select l'ensemble des informations de la table utilisateurs
            $query1 = mysqli_query ($db, 'SELECT * FROM `utilisateurs`');
            //Requête pour affciher le nom des champs
            $query = mysqli_query ($db, 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=\'utilisateurs\'');
        }
        // si c'est pas l'admin qui est connecté c'est ici
        else if ($login != 'admin')
        {
            //tensformation des session en variables
            $id = $_SESSION['id'];
            //Requête select l'ensemble des informations de l'utlisateur connecté
            $query2 = mysqli_query ($db, "SELECT * FROM `utilisateurs` WHERE `id` = $id");
            //Requête pour affciher le nom des champs
            $query = mysqli_query ($db, 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME=\'utilisateurs\'');
        }
    }
?>
    <h1><?php //si l'utilisateur est connecté
            if (isset($_SESSION['login'])) {
                //alors affiche son login
                echo  $_SESSION['login'];
            }
        ?>
         voici vos informations
    </h1>
    <div class="center table">
        <table>
            <thead>
                <tr>
                    <?php 
                        //aficher tous les champs de la table
                        while (( $nomchamp = mysqli_fetch_assoc($query))  != NULL)
                        {
                            foreach ($nomchamp as $key => $value) 
                            {
                                echo '<th>' .$value. '</th>'; 
                            } 
                        }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                    //si c'est admin fetch assoc de toute les infos
                    if ($_SESSION['login'] == 'admin')
                    {
                        while (( $all_result = mysqli_fetch_assoc($query1))  != NULL)
                        {
                        echo '<tr>';
                        foreach ( $all_result as $key => $value)
                        {
                            echo '<td>' .$value.'</td>';
                        }
                        echo '</tr>';
                        } 
                    }
                    //si c'est pas admin fetch all de la personne connecté
                    else if ($_SESSION['login'] != 'admin')
                    {
                        while (( $all_result = mysqli_fetch_assoc($query2))  != NULL)
                        {
                        echo '<tr>';
                        foreach ( $all_result as $key => $value)
                        {
                            echo '<td>' .$value.'</td>';
                        }
                        echo '</tr>';
                        } 
                    }
                ?>
            </tbody>
        </table>
        <div class="signup_link">
            <a href="profil.php">Modifier mon profil</a> <br>
            <a href="index.php">Accueil</a>
        </div>
    </div>
</body>
</html>