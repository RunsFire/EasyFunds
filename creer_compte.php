<!DOCTYPE html>
<?php session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
if (!isset($_SESSION['raison_social'])) {
    $_SESSION['raison_social'] = "%";
    $_SESSION['mail'] = "%";
}
if ($_SESSION['typeu'] != 1 || !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:login.php');
}
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
        <a class="tab" href="admin.php">Client</a>
        <a class="tab active" href="">Créer un compte</a>
        <a class="tab" href="admin_demande.php">Demandes</a>
    </div>
</header>

<body>
    <section class="container">

        <form method="POST" action="creer_compte.php" class="twopart-form">
            <label for="pseudo" style="justify-self: end">Pseudo: </label>
            <input type="text" name="pseudo" required>
            <label for="raison_social" style="justify-self: end">Raison social:</label>
            <input type="text" name="raison_social" required>
            <label for="mail" style="justify-self: end">Mail:</label>
            <input type="email" name="mail" class="forform" required>
            <label for="type_compte" style="justify-self: end">Type de compte:</label>
            <select name="type_compte" required>
                <option value="utilisateur">Utilisateur</option>
                <option value="admin">Admin</option>
            </select>
            <button type="submit" style="justify-self: end">Valider</button>
            <button name="resets" value="resets">Rénitialiser</button>
        </form>

        <?php
        if (isset($_POST['pseudo']) && isset($_POST['raison_social']) && isset($_POST['mail']) && isset($_POST['type_compte'])) {
            $pseudo = $_POST['pseudo'];
            $raison_social = $_POST['raison_social'];
            $mail = $_POST['mail'];
            if ($_POST['type_compte'] == 'utilisateur') {
                $type_compte = 1;
            } else if ($_POST['type_compte'] == 'admin') {
                $type_compte = 0;
            }
            $mdp = uniqid();
            include("connexion.inc.php");
            $test_mail = $cnx->prepare("SELECT mail FROM utilisateur WHERE mail= ?;");
            $test_mail->execute([$mail]);
            if ($test_mail->rowCount() > 0) {
                echo 'ce mail est deja utiliser par un utilisateur';
            } else {
                $result = $cnx->prepare("INSERT utilisateur SET pseudo =?,raison_social =?,mail=?,typeU =?,mdp =?,mdpProvisoire = 1,nbr_essai = 0;");
                if (!$result->execute([$pseudo, $raison_social, $mail, $type_compte, password_hash($mdp, PASSWORD_BCRYPT)])) {
                    echo "Échec de l'ajout de l'utilisateur.";
                } else {
                    $_SESSION['cree_compte_login'] = $mail;
                    $_SESSION['cree_compte_mdp'] = $mdp;
                    include("mail/creecomptemail.php");
                    echo "<p> L'utilisateur a bien été ajouté.";
                }
                unset($_SESSION['cree_compte_login']);
                unset($_SESSION['cree_compte_mdp']);
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