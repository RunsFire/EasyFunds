<?php session_start(); if ($_SESSION['mdpProvisoire']!=1){ header('location:login.php'); } ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Changement de mot de passe</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <img src="easyfunds-icon.png"
            alt="">
        <div class="title">
            Easy Funds
        </div>
    </header>
    <main>
        <section>
            <div class="cat">
                Identifiez-vous
            </div>
            <form method="POST" action="mdpmdp.php">
                <div class="center">
                    <label for="mdp">Mot de passe</label>
                    <input type="password" name="mdp" placeholder="Entrez votre mot de passe" required><br>
                    <button type="button" class="here" id="toggle-password" onclick="togglePasswordVisibility()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </button>
                    <label for="mdp2">Confirmer votre Mot de passe</label>
                    <input type="password" name="mdp2" placeholder="Entrez une seconde fois votre mot de passe"
                        required>
                    <button type="button" class="here" id="toggle-password" onclick="togglePasswordVisibility2()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </button>
                    <?php
                include("connexion.inc.php");
                if (isset($_POST['mdp2']) && isset($_POST['mdp'])){
                    $mdp = $_POST['mdp'];
                    $mdp2 = $_POST['mdp2'];
                    if ($mdp==$mdp2){
                        $cnx->exec("UPDATE utilisateur SET mdpprovisoire='0',mdp='".password_hash($mdp,PASSWORD_BCRYPT)."' WHERE mail='".$_SESSION['login']."';");
                        echo "<br><br>Votre mot de passe a bien été changé";
                        if ( $_SESSION['typeu']==1 ){
                            unset($_SESSION['mdpProvisoire']);
                            $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='".$_SESSION['login']."';");
                        echo "<br><a href=\"accueilUser.php\">Cliquez ici</a> pour aller sur la page d'accueil";
                    }else if ($_SESSION['typeu']==2 ){
                        unset($_SESSION['mdpProvisoire']);
                        echo "<br><a href=\"accueilAdmin.php\">Cliquez ici</a> pour aller sur la page d'accueil";
                        $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='".$_SESSION['login']."';");
                    }
                }else{
                        echo "Les deux mots de passe ne correspondent pas";
                    }
                   
                }
            ?>
                    <br><br>
                    <input type="submit" name="submit" value="Changer mon mot de passe" /><br><br>
                </div>
            </form>
        </section>
        <script src="form.js"></script>
    </main>
</body>

</html>