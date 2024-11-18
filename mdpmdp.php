<?php session_start();
if ($_SESSION['mdpProvisoire'] != 1) {
    header('location:login.php');
} ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Changement de mot de passe</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" type="image/png" href="/img/easyfunds-icon.png">
    <link rel="stylesheet" type="text/css" media="screen" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
</head>

<body>
    <header>
        <img src="/img/easyfunds-icon.png" alt="">
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
                <label for="mdp">Mot de passe</label><br />
                <input type="password" name="mdp" id="password-input" placeholder="Entrez votre mot de passe" required>
                <button type="button" class="here" id="toggle-password" onclick="togglePasswordVisibility()"
                    tabindex="-1">
                    <i id="eye-icon" class="eye"></i>
                </button>
                <br><br><br>
                <label for="mdp2">Confirmer votre Mot de passe</label><br />
                <input type="password" name="mdp2" id="cpassword"
                    placeholder="Entrez une seconde fois votre mot de passe" required>
                <button type="button" class="here" id="toggle-password" onclick="togglePasswordVisibility2()"
                    tabindex="-1">
                    <i id="eye-icon2" class="eye"></i>
                </button>
                <?php
                include("connexion.inc.php");
                if (isset($_POST['mdp2']) && isset($_POST['mdp'])) {
                    $mdp = $_POST['mdp'];
                    $mdp2 = $_POST['mdp2'];
                    if ($mdp == $mdp2) {
                        $cnx->exec("UPDATE utilisateur SET mdpprovisoire='0',mdp='" . password_hash($mdp, PASSWORD_BCRYPT) . "' WHERE mail='" . $_SESSION['login'] . "';");
                        echo "<br><br><h4 class='alert_worked'>Votre mot de passe a bien été changé</h4>";
                        if ($_SESSION['typeu'] == 0) {
                            unset($_SESSION['mdpProvisoire']);
                            $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='" . $_SESSION['login'] . "';");
                            echo "<br><div style='color: white'><a href=\"/Utilisateurs/tresorerie_utilisateur.php\" class='white_link'>Cliquez ici</a> pour aller sur la page d'accueil</div>";
                        } else if ($_SESSION['typeu'] == 1) {
                            unset($_SESSION['mdpProvisoire']);
                            echo "<br><div style='color: white'><a href=\"/Admin/admin_demande.php\" class='white_link'> Cliquez ici</a> pour aller sur la page d'accueil</div>";
                            $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='" . $_SESSION['login'] . "';");
                        } else if ($_SESSION['typeu'] == 2) {
                            unset($_SESSION['mdpProvisoire']);
                            echo "<br><div style='color: white'><a href=\"/PO/tresoreriepo.php\" class='white_link'> Cliquez ici</a> pour aller sur la page d'accueil</div>";
                            $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='" . $_SESSION['login'] . "';");
                        }
                    } else {
                        echo "<br><br><br><h4 class='alert'>Les deux mots de passe ne correspondent pas<h4/>";
                    }
                }
                ?>
                <br>
                <input type="submit" name="submit" value="Changer mon mot de passe" /><br><br>
            </form>
        </section>
        <script src="form.js"></script>
    </main>
</body>

</html>