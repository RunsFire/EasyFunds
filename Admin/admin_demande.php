<!DOCTYPE html>
<?php session_start();
error_reporting(E_ALL);
ini_set("display_errors", 1);
if ($_SESSION['typeu'] != 1 || !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:../login.php');
}
?>
<html>

<head>
    <link rel="stylesheet" href="../page.css">
    <meta charset="utf-8">
    <title>Demande des Admins</title>
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
        <a class="tab active" href="">Demandes</a>
    </div>
</header>

<body>
    <section class="container">
        <!-- SECTION ABOVE TABLE-DISPLAY -->
        <section>
            <!-- BONJOUR [UTILISATEUR] -->
            <div class="frame greet-user ">
                <?php echo "<p>Bonjour <span class=\"username\" style=\"color:white\">" . $_SESSION['pseudo'] . "</span></p>" ?>
                <a class="disconnect" href="../deconnexion.php">Se déconnecter</a>
            </div>
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- toutes les demandes -->
            <div id="liste-utilisateur" class="display active">

                <!----- TABLEAU, headers + datas ----->
                <div class="table frame">
                    <!-- TABLEAU HEADERS-->
                    <div class="frame table-headers">
                        <table class="frame">
                            <tr>
                                <th style="width:10%">Type de demande</th>
                                <th style="width:15%">Raison_social</th>
                                <th style="width:25%">Mail</th>
                                <th style="width:40%">Commentaire</th>
                                <th style="width:10%">option</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas">
                        <table class="frame">
                            <?php
                            include("../connexion.inc.php");
                            $var = 0;
                            $demandes = $cnx->query("SELECT num_demande, raison_social,mail,type_demande,info_supplementaire FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE typeU<3 ORDER BY type_demande,num_demande DESC ;");
                            if ($demandes == null) {
                                echo "Pas de demande";
                            } else {
                                while ($ligne = $demandes->fetch(PDO::FETCH_OBJ)) {
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\">";
                                    } else {
                                        echo "<tr class=\"style2\">";
                                    } ?>
                            <td style="width:10%">
                                <?php
                                        $type_demande = $ligne->type_demande;
                                        if ($type_demande == "b") {
                                            echo "Débloquer";
                                        } else if ($type_demande == "s") {
                                            echo "Supprimer";
                                        } else if ($type_demande == "c") {
                                            echo "Créer";
                                        }
                                        ?></td>
                            <td style="width:15%"><?= $ligne->raison_social ?>N</td>
                            <td style="width:25%"><?= $ligne->mail ?></td>
                            <td style="width:40%"><?= $ligne->info_supplementaire ?></td>
                            <td style="width:10%">
                                <bouton
                                    onclick="actions('<?= $ligne->num_demande ?>','Voulez vous refuser cette demande?','supp_demande.php')"
                                    class="table-butt">
                                    Refuser
                                </bouton><br><br>
                                <?php
                                        if ($type_demande == "b") { ?>
                                <bouton
                                    onclick="actions('<?= $ligne->num_demande ?>','Voulez vous débloquer cet utilisateur?','debloquer_utilisateur.php')"
                                    class="table-butt">
                                    Débloquer
                                </bouton>
                                <?php } else if ($type_demande == "s") { ?>
                                <bouton
                                    onclick="actions('<?= $ligne->num_demande ?>','Voulez vous supprimer cet utilisateur?','supp_utilisateur.php')"
                                    class="table-butt">
                                    Supprimer
                                </bouton>
                                <?php } else if ($type_demande == "c") { ?>
                                <bouton
                                    onclick="actions('<?= $ligne->num_demande ?>','Voulez vous créer un compte pour cet utilisateur?','creer_compte.php')"
                                    class="table-butt">
                                    Créer
                                </bouton>
                                <?php } ?>
                            </td>
                            </tr>
                            <?php
                                    $var++;
                                }
                            }
                            $demandes->closeCursor();
                            ?>
                        </table>
                    </div>
                </div>
        </section>
        <?php
        // permet d'afficher si une action a échouer ou a été effectuer
        function afficher_alert($name_var_session, $mess_effectuer, $mess_echouer)
        {
            // on verifie que la variable de session existe
            if (isset($_SESSION[$name_var_session])) {
                // si variable = effectuer on affiche le message mess_effectuer
                if ($_SESSION[$name_var_session] == "effectuer") {
                    echo "
                        <script>
                            alert(\" $mess_effectuer \");
                        </script>";
                } // sinon on affiche le mess_echouer
                else if ($_SESSION[$name_var_session] == "echouer") {
                    echo "
                        <script>
                            alert(\" $mess_echouer \");
                        </script>";
                } // on supprime la variable
                unset($_SESSION[$name_var_session]);
            }
        }
        afficher_alert("supp_demande", "La demande a bien été refusé", "Nous n'avons pas pu supprimé la demande!");
        afficher_alert("debloquer", "Le compte a bien été debloquer", "Nous n'avons pas pu débloquer ce compte!");
        afficher_alert("supp_utilisateur", "L'utilisateur a bien été supprimer", "Nous n'avons pas pu supprimé l'utilisateur!");
        afficher_alert("creer_utilisateur", "L'utilisateur a bien été créer", "");
        ?>
        <script>
        function actions(num, question, page) {
            // quand on clique sur une actions une question s'affiche 
            const result = confirm(question);
            // si on clique sur ok on est rediriger vers une page qui effectue l'action.
            if (result) {
                document.location.replace(page + '?num=' + num);
            }
        }
        </script>

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