<!DOCTYPE html>
<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
if ($_SESSION['typeu'] != 1 || !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:../login.php');
}
include("../connexion.inc.php");
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Créer un compte</title>
    <link rel="icon" type="image/png" href="easyfunds-icon.png">
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
        <a class="tab active" href="">Créer un compte</a>
        <a class="tab" href="admin_demande.php">Demandes</a>
    </div>
</header>

<body>
    <section class="container">

        <form method="POST" action="creer_compte.php">
            <label for="pseudo">Pseudo: </label>
            <input type="text" name="pseudo" required><br>
            <input type="numeric" name="num" required hidden value="<?php if (isset($_GET['num'])) {
                                                                        echo $_GET['num'];
                                                                    }
                                                                    ?>">
            <label for="raison_social">Raison social:</label>
            <input type="text" name="raison_social" required><br>
            <label for="mail">Mail:</label>
            <input type="text" name="mail" required><br>
            <label for="type_compte">Type de compte:</label>
            <select name="type_compte" required>
                <option value="utilisateur">Utilisateur</option>
                <option value="admin">Admin</option>
            </select><br>
            <button type="submit">Valider</button>
            <button name="resets" value="resets">Rénitialiser</button>
        </form>

        <?php
        function suppDemande($num)
        {
            //supprimer la demande
            global $cnx;
            $result = $cnx->exec("DELETE FROM demande_compte WHERE num_demande = $num");
            return $result;
        }
        // si le formulaire a été envoyer
        if (isset($_POST['pseudo']) && isset($_POST['raison_social']) && isset($_POST['mail']) && isset($_POST['type_compte'])) {
            // on initialise les variables
            $pseudo = $_POST['pseudo'];
            $raison_social = $_POST['raison_social'];
            $mail = $_POST['mail'];
            if ($_POST['type_compte'] == 'utilisateur') {
                $type_compte = 0;
            } else if ($_POST['type_compte'] == 'admin') {
                $type_compte = 1;
            }
            $mdp = uniqid(); // on genere un mot de passe provisoire
            // on verifie que le mail n'est pas deja utiliser
            $test_mail = $cnx->prepare("SELECT mail FROM utilisateur WHERE mail= ?;");
            $test_mail->execute([$mail]);
            if ($test_mail->rowCount() > 0) {
                echo 'ce mail est deja utiliser par un utilisateur';
            } else {
                // si le mail n'est pas deja utiliser on insert dans la bdd
                $result = $cnx->prepare("INSERT utilisateur SET pseudo =?,raison_social =?,mail=?,typeU =?,mdp =?,mdpProvisoire = 1,nbr_essai = 0;");
                if (!$result->execute([$pseudo, $raison_social, $mail, $type_compte, password_hash($mdp, PASSWORD_BCRYPT)])) {
                    echo "Échec de l'ajout de l'utilisateur.";
                } else {
                    $num = $_POST["num"];
                    // on envoie un mail a l'utilisateur
                    $_SESSION['cree_compte_login'] = $mail;
                    $_SESSION['cree_compte_mdp'] = $mdp;
                    include("../mail/creecomptemail.php");
                    $_SESSION['creer_utilisateur'] = "effectuer";
                    // on supprime la demande
                    suppDemande($num);
                }
                // on supprimer les variables provisoire
                unset($_SESSION['cree_compte_login']);
                unset($_SESSION['cree_compte_mdp']);
                // on redirige vers la page de demande
                header('Location:admin_demande.php');
            }
        }
        ?>
    </section>


    <!-- FOOTER -->
    <footer>

        <script>
        //Option : tous-clients / par-client
        function displayTable(optionId, displayId) {
            //remove checked from all options
            const allOptionsRadio = document.querySelectorAll(" .option-radio");
            allOptionsRadio.forEach(radio => {
                radio.checked = false
            });
            //add checked to option
            const toCheck = document.getElementById(optionId);
            toCheck.checked = true;
            //remove active from all displays
            const allDisplays = document.querySelectorAll(".display");
            allDisplays.forEach(display => {
                display.classList.remove("active");
            })
            //add active to display
            const toDisplay = document.getElementById(displayId);
            toDisplay.classList.add("active");
        }
        </script>

    </footer>
</body>

</html>