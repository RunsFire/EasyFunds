<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Connexion</title>
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
            <form method="POST" action="login.php">
                <label for="mail">Mail</label><br>
                <input type="text" name="mail" id="mail" placeholder="exemple@gmail.com" style="margin-bottom: 2rem"
                    required><br>
                <label for="password">Mot de passe</label><br>
                <input type="password" name="mdp" id="password-input" placeholder="p4as5W0rD">
                <button type="button" class="here" id="toggle-password" onclick="togglePasswordVisibility()">
                    <i id="eye-icon" class="eye"></i>
                </button>
                <div class="rightnote"> <a href="mdpoublie.php">Mot de passe oubli&eacute; ?</a></div>
                <?php
                include("connexion.inc.php");
                if (isset($_POST['mail']) && isset($_POST['mdp'])) {
                    $_SESSION['login'] = $_POST['mail'];
                    $_SESSION['mdp'] = $_POST['mdp'];
                    $requete = $cnx->query("SELECT mdp,typeu,mdpprovisoire,num,mail,nbr_essai,pseudo FROM utilisateur WHERE mail='" . $_SESSION['login'] . "';");
                    $row = $requete->fetch();
                    if ($row == 0 || !(password_verify($_SESSION['mdp'], $row[0])) || $row[5] == 3) {
                        if ($row == 0) {
                            echo "<h4 class='alert'>login ou mot de passe incorrect</h4>";
                        }
                        if ($row != 0 && $row[5] != 3) {
                            $cnx->exec("UPDATE utilisateur SET nbr_essai=nbr_essai+1 WHERE mail='" . $_SESSION['login'] . "';");
                            $row[5]++;
                        }
                        if ($row != 0 && $row[5] < 2) {
                            echo "<h4 class='alert'>login ou mot de passe incorrect</h4>";
                        } else if ($row != 0 && $row[5] == 2) {
                            echo "<h4 class='alert'>ATTENTION : PLUS QUE UN ESSAI AVANT LE BLOCAGE DU COMPTE </h4>";
                        } else if ($row != 0 && $row[5] == 3) {
                            $_SESSION['num'] = $row[3];
                            echo "<h4 class='alert'>Votre compte est maintenant bloqué. Contactez un admin en<a href=\"contact_admin.php\"> cliquant ici </a></h4>";
                        }

                        unset($_SESSION['login']);
                        unset($_SESSION['mdp']);
                    } else {
                        $_SESSION['typeu'] = $row[1];
                        $_SESSION['mdpProvisoire'] = $row[2];
                        $_SESSION['num'] = $row[3];
                        $_SESSION['pseudo'] = $row[6];
                        $cnx->exec("UPDATE utilisateur SET nbr_essai=0 WHERE mail='" . $_SESSION['login'] . "';");
                        if ($row[2] == 1) {
                            header('location:mdpmdp.php');
                        } else if ($_SESSION['typeu'] == '0' && $row[2] == 0) {
                            header('location:/Utilisateurs/tresorerie_utilisateur.php');
                        } else if ($_SESSION['typeu'] == '1' && $row[2] == 0) {
                            header('location:/Admin/admin_demande.php');
                        } else if ($_SESSION['typeu'] == '2' && $row[2] == 0) {
                            header('location:/PO/tresoreriepo.php');
                        }
                    }
                }
                echo "<br>";
                ?>
                <input type="submit" value="Se connecter" />
            </form>
        </section>
        <p class="slogan">
            Easy Funds,<br>la banque <br> qui simplifie <br> votre quotidien.
        </p>
        <script src="form.js"></script>
    </main>
</body>

</html>