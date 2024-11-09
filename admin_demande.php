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
                <a class="disconnect" href="deconnexion.php">Se déconnecter</a>
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
                            include("connexion.inc.php");
                            $var = 0;
                            $demandes = $cnx->query("SELECT num_demande, raison_social,mail,type_demande,info_supplementaire FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE typeU<3 ORDER BY type_demande ;");
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
                                <bouton onclick="refuser_demande('<?= $ligne->num_demande ?>')" class="table-butt">
                                    Refuser
                                </bouton><br><br>
                                <?php
                                        if ($type_demande == "b") { ?>
                                <bouton onclick="debloquer_utilisateur('<?= $ligne->num_demande ?>')"
                                    class="table-butt">
                                    Débloquer
                                </bouton>
                                <?php } else if ($type_demande == "s") { ?>
                                <bouton onclick="supprimer_utilisateur('<?= $ligne->num_demande ?>')"
                                    class="table-butt">
                                    Supprimer
                                </bouton>
                                <?php } else if ($type_demande == "c") { ?>
                                <bouton onclick="creer_utilisateur('<?= $ligne->num_demande ?>')" class="table-butt">
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
        function afficher_alert($name_val_session, $mess_effectuer, $mess_echouer)
        {
            if (isset($_SESSION[$name_val_session])) {
                if ($_SESSION[$name_val_session] == "effectuer") {
                    echo "
                        <script>
                            alert('$mess_effectuer');
                        </script>";
                } else if ($_SESSION[$name_val_session] == "echouer") {
                    echo "
                        <script>
                            alert('$mess_echouer');
                        </script>";
                }
                unset($_SESSION[$name_val_session]);
            }
        }
        afficher_alert("supp_demande", "La demande a bien été refusé", "Nous n'avons pas pu supprimé la demande!");
        afficher_alert("debloquer", "Le compte a bien été debloquer", "Nous n'avons pas pu débloquer ce compte!");
        afficher_alert("supp_utilisateur", "L'utilisateur a bien été supprimer", "Nous n'avons pas pu supprimé l'utilisateur!");
        afficher_alert("creer_utilisateur", "L'utilisateur a bien été créer", "");
        ?>
        <script>
        function refuser_demande(num, mail, type) {
            const result = confirm('Voulez vous refuser cette demande?');
            if (result) {
                document.location.replace('supp_demande.php?num=' + num);
            }
        }

        function supprimer_utilisateur(num) {
            const result = confirm('Voulez vous supprimer cet utilisateur?');
            if (result) {
                document.location.replace('supp_utilisateur.php?num=' + num);
            }
        }

        function debloquer_utilisateur(num) {
            const result = confirm('Voulez vous débloquer cet utilisateur?');
            if (result) {
                document.location.replace('debloquer_utilisateur.php?num=' + num);
            }
        }

        function creer_utilisateur(num) {
            const result = confirm('Voulez vous créer un compte pour cet utilisateur?');
            if (result) {
                document.location.replace('creer_compte.php?num=' + num);
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