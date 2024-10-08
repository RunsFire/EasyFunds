<label?php session_start(); if ($_SESSION['mdpProvisoire']!=1){ header('location:page-accueil.html'); } ?>
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
            <img src="https://images.theconversation.com/files/311566/original/file-20200123-162199-1qn3vm.jpg?ixlib=rb-4.1.0&q=45&auto=format&w=926&fit=clip"
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
                        <label for="mdp2">Confirmer votre Mot de passe</label>
                        <input type="password" name="mdp2" placeholder="Entrez une seconde fois votre mot de passe"
                            required>
                        <?php
                include("connexion.inc.php");
                if (isset($_POST['mdp2']) && isset($_POST['mdp'])){
                    $mdp = $_POST['mdp'];
                    $mdp2 = $_POST['mdp2'];
                    if ($mdp==$mdp2){
                        $cnx->exec("UPDATE\"silvereconomy\".\"log\" SET \"mdpProvisoire\"='0',mdp='".password_hash($mdp,PASSWORD_BCRYPT)."' WHERE login='".$_SESSION['login']."';");
                        echo "<br><br>Votre mot de passe a bien été changé";
                        if ( $_SESSION['typeu']==1 ){
                            unset($_SESSION['mdpProvisoire']);
                            $cnx->exec("UPDATE \"easyfunds\".\"utilisateur\" SET \"nbr_essai\"=0 WHERE \"mail\"='".$_SESSION['login']."';");
                        echo "<br><a href=\"accueilUser.php\">Cliquez ici</a> pour aller sur la page d'accueil";
                    }else if ($_SESSION['typeu']==2 ){
                        unset($_SESSION['mdpProvisoire']);
                        echo "<br><a href=\"accueilAdmin.php\">Cliquez ici</a> pour aller sur la page d'accueil";
                        $cnx->exec("UPDATE \"easyfunds\".\"utilisateur\" SET \"nbr_essai\"=0 WHERE \"mail\"='".$_SESSION['login']."';");
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
        </main>
    </body>

    </html>