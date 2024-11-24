<?php session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '2') {
    header('location:login.php');
}
include("connexion.inc.php");
$ok = false;
if (isset($_POST["message"])) {
    $message = $_POST['message'];
    $type = $_POST['typedemande'];
    $num = $_SESSION['num'];
    $r = $cnx->exec("INSERT INTO demande_compte(type_demande,info_supplementaire) VALUES ('$type','$message') ; ");
    $ok = true;
} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter un administrateur</title>
    <link rel="stylesheet" href="page.css">
    <link rel="icon" type="image/png" href="easyfunds-icon.png">
</head>
</head>
<!-- HEADER -->
<header>
    <!-- ICON -->
    <div class="logo">
        <img src="easyfunds-icon.png" class="small-icon">
        <img src="easyfund-logo.png" class="small-logo">
    </div>

    <!-- ONGLETS -->
    <div class="tabs">
        <a class="tab " href="tresoreriepo.php">Trésorerie</a>
        <a class="tab" href="remisespo.php">Remises</a>
        <a class="tab" href="impayespo.php">Impayés</a>
        <a class="tab active" href="">Demandes</a>
    </div>
</header>

<body>
<section class="container">
    <section>
        <!-- BONJOUR [UTILISATEUR] -->
        <div class="frame greet-user ">
            <?php echo "<p>Bonjour <span class=\"username\" style=\"color:white\">".$_SESSION['pseudo']."</span></p>" ?>
            <a class="disconnect" href="deconnexion.php">Se déconnecter</a>
        </div>
    </section>
    <h1 class="pttl">Contacter un administrateur</h1>
    <main class="dmdad">
        <section>
            <div class="cat">
                <?php
                if ($ok) { ?>
                <h4 class="alert_worked">Votre demande a bien &eacute;t&eacute; prise en compte.</h4>
                <?php
                $req = $cnx->query("SELECT mail from  utilisateur WHERE typeu='1'");
                while ($ligne = $req->fetch(PDO::FETCH_OBJ)) {
                    $_SESSION['mail_admin'] = $ligne->mail;
                    if ($type == 'c') {
                        $_SESSION['type'] = "creation";
                    } else {
                        $_SESSION['type'] = 'suppression';
                    }
                    include("maildemandepo.php");
                }
                $req->closeCursor();
                unset($_SESSION['mail_admin']);
                unset($_SESSION['type']);
                ?>
            </div>
            <?php
            } else {
                ?>
                <h6 class="sbttl">Type de requête</h6>
                <form method="POST" action="">
                    <br>
                    <select name=typedemande required>
                        <option value="" disabled>--Sélectionner une option--</option>
                        <option value="c">Création d'un compte</option>
                        <option value="s">Suppresion d'un compte</option>
                    </select><br>
                    <textarea class="ta-grey" name="message" placeholder="Entrez votre message"
                              rows="6" cols="40" required></textarea><br>
                    <input type="submit" name="submit" value="Envoyer" class="sub-cent"/><br><br>
                </form>
                <?php
            }
            ?>
        </section>
        <script src="form.js"></script>
    </main>
</section>
</body>

</html>