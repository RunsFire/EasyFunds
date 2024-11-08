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
        <a class="tab" href="creer_compte.php">Créer un compte</a>
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
                                <th style="width:20%">Raison_social</th>
                                <th style="width:20%">Mail</th>
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
                            $demandes = $cnx->query("SELECT num_demande, raison_social,mail,type_demande,info_supplementaire FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE typeU<3 ;");
                            if ($demandes == null) {
                                echo "Pas de demande";
                            } else {
                                while ($ligne = $demandes->fetch(PDO::FETCH_OBJ)) {
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\">";
                                    } else {
                                        echo "<tr class=\"style2\">";
                                    } ?>
                                    <td style="width:10%"><?php
                                                            $type_demande = $ligne->type_demande;
                                                            if ($type_demande == "b") {
                                                                echo "Bloquer";
                                                            } else if ($type_demande == "s") {
                                                                echo "Supprimer";
                                                            } else if ($type_demande == "c") {
                                                                echo "Créer";
                                                            }
                                                            ?></td>
                                    <td style="width:20%"><?= $ligne->raison_social ?>N</td>
                                    <td style="width:20%"><?= $ligne->mail ?></td>
                                    <td style="width:40%"><?= $ligne->info_supplementaire ?></td>
                                    <td style="width:10%">
                                        <bouton onclick="refuser_demande('<?= $ligne->num_demande ?>')">Refuser</bouton>
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
        if (isset($_SESSION["supp_demande"])) {
            if ($_SESSION["supp_demande"] == "effectuer") {
                echo "
                    <script>
                        alert('La demande a bien été refusé');
                    </script>";
            } else if ($_SESSION["supp_demande"] == "echouer") {
                echo "
                    <script>
                        alert('Nous n'avons pas pu supprimé la demande!');
                    </script>";
            }
        }
        ?>
        <script>
            function refuser_demande(num) {
                const result = confirm('Voulez vous refuser cette demande');
                if (result) {
                    document.location.replace('supp_demande.php?num=' + num);
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