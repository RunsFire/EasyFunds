<!DOCTYPE html>
<?php session_start (); 
error_reporting(E_ALL); 
ini_set("display_errors", 1); 
if (!isset($_SESSION['raison_social'])){
    $_SESSION['raison_social']="%" ; $_SESSION['mail']="%" ; 
} 
    // if($_SESSION['typeu']!=1 || !isset($_SESSION['login'])&& !isset($_SESSION['mdp'])) { 
    // header('location:login.php'); 
    // } 
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Accueil</title>
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
        <a class="tab" href="admin_des_compte_client.php">Client</a>
        <a class="tab active" href="">Créer un compte</a>
        <a class="tab" href="">Demandes</a>
    </div>
</header>

<body>
    <section class="container">

        <!-- SECTION ABOVE TABLE-DISPLAY -->
        <section>
            <!-- BONJOUR [UTILISATEUR] -->
            <div class="frame greet-user ">
                <?php echo "<p>Bonjour <span class=\"username\" style=\"color:white\">".$_SESSION['pseudo']."</span></p>" ?>
                <a class="disconnect" href="deconnexion.php">Se déconnecter</a>
            </div>
        </section>

        <form method="POST" action="creer_compte.php">
            Pseudo: <input type="text" name="pseudo"><br>
            Raison social: <input type="text" name="raison_social"><br>
            Mail: <input type="text" name="mail"><br>
            Type de compte: <select name="type_compte">
                <option value="utilisateur">Utilisateur</option>
                <option value="admin">Admin</option>
            </select><br>
            <button type="submit">Trier</button>
            <button name="resets" value="resets">Rénitialiser</button>
        </form>

        <?php
        if (isset($_POST['pseudo']) && isset($_POST['raison_social']) && isset($_POST['mail']) && isset($_POST['type_compte'])){
            $pseudo = $_POST['pseudo'];
            $raison_social = $_POST['raison_social'];
            $mail = $_POST['mail'];
            if ($_POST['type_compte']== 'utilisateur'){
                $type_compte = 1;
            }else if ($_POST['type_compte']== 'admin'){
                $type_compte = 0;
            }
            include("connexion.inc.php");
            $result = $cnx->prepare("INSERT utilisateur SET pseudo =?,raison_social =?,mail=?,typeU =?, nbr_essai = 0," );
            if (!$stmt->execute([$pseudo, $raison_social, $mail, $type_compte])) {
                echo "Échec de l'ajout de l'utilisateur.";
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