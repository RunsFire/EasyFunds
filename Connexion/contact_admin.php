<?php session_start(); 
include("connexion.inc.php");
$ok = false;
if (isset($_POST["message"])){
    $message = $_POST['message'];
    $num = $_SESSION['num'];
    $cnx -> exec("INSERT INTO \"easyfunds\".\"demande_compte\" (num,type_demande,info_supplementaire) VALUES ($num,'b','$message');");
    $ok = true;
} ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacter un administrateur</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <img src="easyfunds-icon.png" alt="">
        <div class="title">
            Easy Funds
        </div>
    </header>
    <main>
        <section>
            <div class="cat">
                <?php
            if ($ok){?>
                Votre demande a bien été prise en compte
            </div>
            <?php
            }else{
            ?>
            </div>
            <form method="POST" action="">
                <label for="message">Expliquer pourquoi vous vous êtes trompé autant de fois</label>
                <textarea name="message" placeholder="Entrez votre message" rows="6" cols="40" required></textarea><br>
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