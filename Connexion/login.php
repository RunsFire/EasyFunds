<?php
	session_start ();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Itim&display=swap');
        </style>
        
</head>
<body>
    <div class="navbar">
        <a href="page-accueil.html"><img src="image/Silver-Stone.png" alt="" class="taille"></a>
    </div>
    <div class="center-image">
        <img src="image/old_people.png" alt="">
    </div>
    <form method="POST" action="login.php">
            <div class="center">
                <h2 class="gris">Mail de connexion</h2>
                <input type="text" class="champ" name="mail" placeholder="Entrez votre mail" size="40" required><br><br>
                <h2 class="gris">Mot de passe</h2>
                <input type="password" class="champ" name="mdp" id="password-input" placeholder="Mot de passe" size="40" required />
                    <button type="button" id="toggle-password" onclick="togglePasswordVisibility()">
                        <i id="eye-icon" class="fas fa-eye"></i>
                    </button>
            <?php
                include("connexion.inc.php");
                if (isset($_POST['mail']) && isset($_POST['mdp'])){
                    $_SESSION['login'] = $_POST['mail'];
                    $_SESSION['mdp'] = $_POST['mdp'];
                    $requete = $cnx -> query("SELECT \"mdp\",\"typeu\",\"mdpProvisoire\",\"num\",\"mail\",\"nbr_essai\" FROM \"easyfunds\".\"utilisateur\" WHERE \"mail\"='". $_SESSION['login']."';");
                    $row=$requete->fetch();
                    $mdpBDD = $row[0];
                    if ($row==0 || !(password_verify($_SESSION['mdp'],$mdpBDD)) || $row[5]==3){
                        if ($row[5]!=3){
                            $cnx->exec("UPDATE \"easyfunds\".\"utilisateur\" SET \"nbr_essai\"=\"nbr_essai\"+1 WHERE \"mail\"='".$_SESSION['login']."';");
                            $row[5]++;
                        }
                        if ($row[5]<2){
                            echo "<h4 class=\"red\">login ou mot de passe incorrect </h4>";
                        }else if ($row[5]==2){
                            echo "<h4 class=\"red\">ATTENTION : PLUS QUE UN ESSAI AVANT LE BLOCAGE DU COMPTE </h4>";
                        }else if ($row[5]==3){
                            echo "<h4 class=\"red\">Votre compte est maintenant bloqué. Contactez un admin en cliquant ici </h4>"; #RAJOUTER FORMULAIRE CONTACT ADMIN
                        }
                        
                        unset($_SESSION['login']);
                        unset( $_SESSION['mdp']);
                        
                    }
                    else {
                        $_SESSION['typeu']=$row[1];
                        $_SESSION['mdpProvisoire']=$row[2];
                        $_SESSION['num']=$row[3];
                        if ( $_SESSION['typeu']=='0' && $row[2]==0){
                                header('location:accueilUser.php');
                        } else if( $_SESSION['typeu']=='0' &&$row[2]==1){
                                header('location:mdpmdp.php');
                        }
                         else if ( $_SESSION['typeu']=='1' && $row[2]==0 ){
                                header('location:accueilAdmin.php');
                         }else if ( $_SESSION['typeu']=='1' && $row[2]==1 ){
                                header('location:mdpmdp.php');
                        }}
                    }
            echo "<br><br>";
            echo "<input class =\"btn\" type=\"submit\" name=\"submit\" value=\"Connexion\" /><br><br>";
            echo "</form>";
            if (isset($_POST['mail']) && isset($_POST['mdp'])){
                if ($row[5]!=3){
                    echo "Mot de passe oublié ? <a href=\"mdpoublie.php\">  cliquez ici </a><br><br>";
                }
            }else{
                echo "Mot de passe oublié ? <a href=\"mdpoublie.php\">  cliquez ici </a><br><br>";
            }
            ?>
            </div>
            <h2 class="itim-regular green center">Unis pour le bien-être : <br> 
            rencontres entre maisons de retraite.</h2>
            </div>
<script src="script/form.js"></script>
    </div> 
</body>
</html>