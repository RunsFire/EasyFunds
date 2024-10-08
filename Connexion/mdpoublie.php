<label?php session_start (); ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mot de passe oublié</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>
        <header>
            <img src="https://images.theconversation.com/files/311566/original/file-20200123-162199-1qn3vm.jpg?ixlib=rb-4.1.0&q=45&auto=format&w=926&fit=clip"
                alt="">
            <div class="title">
                Easy Funds
            </div>
        </header>
        <main>
            <section>
                <div class="cat">
                    Mot de passe oublié
                </div>
                <form method="POST" action="mdpoublie.php">
                    <div class="center">
                        <label for="mail">Mail de connexion</label>
                        <input type="text" name="mail" id="mail" placeholder="Entrez votre mail" required><br><br>
                        <?php
                include("connexion.inc.php");
                if (isset($_POST['mail'])){
                    $_SESSION['login'] = $_POST['mail'];
                    $requete = $cnx -> query("SELECT \"mail\", \"nbr_essai\" FROM \"easyfunds\".\"utilisateur\" WHERE \"mail\"='". $_SESSION['login']."';");
                    $row=$requete->fetch();
                    if ($row==0){
                        echo "<h4>le mail n'est pas enregistré</h4>";
                    }else if ($row[1]>=3){
                        echo "<h4>Votre compte étant bloqué, vous ne pouvez pas changer votre mot de passe.</h4>";
                    }
                    else {
                        $_SESSION['mdp']= uniqid();
                        include("mail/mdpoubliemail.php");
                        $cnx->exec("UPDATE \"easyfunds\".\"utilisateur\" SET \"mdpProvisoire\"='1',\"mdp\"='".password_hash($_SESSION['mdp'],PASSWORD_BCRYPT)."' WHERE \"mail\"='".$_SESSION['login']."';");
                        echo "Un mail vous a été envoyé. <br> Ce mail contient un code provisoire qui vous permettra de vous connecter à votre compte.";
                    }
                }
            ?>
                        <input type="submit" name="submit" value="Envoyez un code" /><br><br>
                        <div>Pour revenir à l'accueil <a href="page-accueil.html"> cliquez ici </a></div><br><br>
                    </div>
                </form>
            </section>
        </main>
    </body>

    </html>