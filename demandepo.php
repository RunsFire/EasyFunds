<?php session_start(); 
 if (!isset( $_SESSION['typeu']) || $_SESSION['typeu']!='2'){
    header('location:login.php');
} 
include("connexion.inc.php");
$ok = false;
if (isset($_POST["message"])){
    $message = $_POST['message'];
    $type = $_POST['typedemande'];
    $num = $_SESSION['num'];
    $r = $cnx -> exec("INSERT INTO demande_compte(type_demande,info_supplementaire) VALUES ('$type','$message') ; ");
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
        <a class="tab" href="">Impayés</a>
        <a class="tab active" href="">Demandes</a>
    </div>
</header>
<body>
    <main>
        <section>
            <div class="cat">
                <?php
            if ($ok){?>
                Votre demande a bien été prise en compte
                <?php
                    $req = $cnx->query("SELECT mail from  utilisateur WHERE typeu='1'");
                    while( $ligne = $req->fetch(PDO::FETCH_OBJ)){
                        $_SESSION['mail_admin']=$ligne->mail;
                        if ($type=='c'){
                            $_SESSION['type']="creation";
                        }else{
                            $_SESSION['type']='suppression';
                        }
                        include("mail/maildemandepo.php");
                    }$req->closeCursor();
                    unset($_SESSION['mail_admin']);
                    unset($_SESSION['type']);
                ?>
            </div>
            <?php
            }else{
            ?>
            </div>
            <form method="POST" action="">
                <br>
                <select name=typedemande required>
                <option value="">--Sélectionner une option--</option>
                <option value="c">Création d'un compte</option>
                <option value="s">Suppresion d'un compte</option>
                </select><br>
                <textarea style="color: rgba(255,255,255,0.25)" name="message" placeholder="Entrez votre message" rows="6" cols="40" required></textarea><br>
                <input type="submit" name="submit" value="Envoyer" /><br><br>
            </form>
            <?php
            }
            ?>
        </section>
        <script src="form.js"></script>
    </main>
</body>

</html>