<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '2') {
    header('location:../login.php');
}
if (!isset($_SESSION['siren3'])) {
    $_SESSION['siren3'] = "%";
    $_SESSION['raison3'] = "%";
    $_SESSION['date3'] = "0000-00-00";
    $_SESSION['date4'] = "3000-01-01";
    $_SESSION['numdossier'] = "%";
}
include("../connexion.inc.php");
?>
<html>

<head>
    <link rel="stylesheet" href="../page.css">
    <meta charset="utf-8">
    <title>Impayés des clients</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/jspdf/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="easyfunds-icon.png">
</head>

<!-- HEADER -->
<header>
    <!-- ICON -->
    <div class="logo">
        <img src="easyfunds-icon.png" class="small-icon">
        <img src="/img/easyfund-logo.png" class="small-logo">
    </div>

    <!-- ONGLETS -->
    <div class="tabs">
        <a class="tab" href="tresoreriepo.php">Trésorerie</a>
        <a class="tab" href="remisespo.php">Remises</a>
        <a class="tab active" href="impayespo.php">Impayés</a>
        <a class="tab" href="demandepo.php">Demandes</a>
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

            <div class="frame options">

                <!-- LISTE IMPAYES DES COMPTES CLIENTS -->
                <a href="impayespo.php" class="option active">Liste</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="graphimpayespo.php" class="option">Somme des impayés</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="evolutionimpaye.php" class="option">Évolution des impayés</a>

            </div>
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- PO PAR CLIENT, vide par défaut -->
            <div id="po-par-client-display" class="display active">
                <!-- FILTRES -->
                <div class="frame filtres">
                    <form method="POST" action="impayespo.php">
                        <?php
                        if (!empty($_POST['siren'])) {
                            echo '<input type="text" name="siren" class="filtre" value=' . $_POST['siren'] . ' placeholder="SIREN">';
                        } else {
                            echo '<input type="text" name="siren" class="filtre" placeholder="SIREN">';
                        }
                        if (!empty($_POST['raison'])) {
                            echo '<input type="text" name="raison" class="filtre" value=' . $_POST['raison'] . ' placeholder="Raison sociale">';
                        } else {
                            echo '<input type="text" name="raison" class="filtre" placeholder="Raison sociale">';
                        }
                        if (!empty($_POST['date'])) {
                            echo '<input type="date" name="date" class="filtre" value=' . $_POST['date'] . '>';
                        } else {
                            echo '<input type="date" name="date" class="filtre">';
                        }
                        if (!empty($_POST['date2'])) {
                            echo '<input type="date" name="date2" class="filtre" value=' . $_POST['date2'] . '>';
                        } else {
                            echo '<input type="date" name="date2" class="filtre">';
                        }
                        if (!empty($_POST['numdossier'])) {
                            echo '<input type="number" name="numdossier" class="filtre" value=' . $_POST['numdossier'] . ' min="0" placeholder="numéro dossier" >';
                        } else {
                            echo '<input type="number" name="numdossier" class="filtre" min="1" placeholder="numéro dossier">';
                        }
                        ?>
                        <button type="submit" class="search">Rechercher</button>
                        <button name="reset" value="reset" class="search">Rénitialiser</button>
                    </form>
                </div>

                <!----- TABLEAU, headers + datas ----->
                <div class="table frame" id="po-tous-clients">
                    <!-- TABLEAU HEADERS-->
                    <div class="frame table-headers">
                        <table class="frame">
                            <tr>
                                <th style="width:20%">SIREN</th>
                                <th style="width:20%">raison sociale</th>
                                <th style="width:20%">Date vente</th>
                                <th style="width:20%">Date remise</th>
                                <th style="width:25%">Carte</th>
                                <th style="width:10%">Réseau</th>
                                <th style="width:20%">Numéro dossier</th>
                                <th style="width:20%">Montant</th>
                                <th style="width:20%">code impayé</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas shorter-table">
                        <table class="frame">
                            <?php
                            $var = 0;
                            if (!empty($_POST['siren'])) {
                                $_SESSION['siren3'] = $_POST['siren'] . "%";
                            }
                            if (!empty($_POST['raison'])) {
                                $_SESSION['raison3'] = $_POST['raison'] . "%";
                            }
                            if (!empty($_POST['date'])) {
                                $_SESSION['date3'] = $_POST['date'] . "%";
                            }
                            if (!empty($_POST['date2'])) {
                                $_SESSION['date4'] = $_POST['date2'] . "%";
                            }
                            if (!empty($_POST['numdossier'])) {
                                $_SESSION['numdossier'] = $_POST['numdossier'];
                            }
                            if (!empty($_POST['reset'])) {
                                $_SESSION['siren3'] = "%";
                                $_SESSION['raison3'] = "%";
                                $_SESSION['date3'] = "0000-00-00";
                                $_SESSION['date4'] = "3000-01-01";
                                $_SESSION['numdossier'] = "%";
                                unset($_POST['reset']);
                            }
                            if (!empty($_POST['resets'])) {
                                unset($_SESSION['filtre']);
                                unset($_SESSION['croissance']);
                                unset($_POST['filtre']);
                                unset($_POST['croissance']);
                                unset($_POST['resets']);
                            }
                            if (!empty($_POST['filtre']) && !empty($_POST['croissance'])) {
                                $_SESSION['filtre'] = $_POST['filtre'];
                                $_SESSION['croissance'] = $_POST['croissance'];
                            }
                            if (isset($_SESSION['filtre']) &&  isset($_SESSION['croissance'])) {
                                $tresorerie = $cnx->query("SELECT SIREN,raison_sociale,date_vente,date_remise,NCarte,reseau,numero_impaye,montant,code_impaye FROM impaye WHERE SIREN LIKE \"" . $_SESSION['siren3'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison3'] . "\" AND date_vente >= \"" . $_SESSION['date3'] . "\" AND date_remise<= \"" . $_SESSION['date4'] . "\" AND numero_impaye LIKE   \"" . $_SESSION['numdossier'] . "\" ORDER BY " . $_SESSION['filtre'] . " " . $_SESSION['croissance'] . ";");
                            } else {
                                $tresorerie = $cnx->query("SELECT SIREN,raison_sociale,date_vente,date_remise,NCarte,reseau,numero_impaye,montant,code_impaye FROM impaye WHERE SIREN LIKE\"" . $_SESSION['siren3'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison3'] . "\"AND date_vente >= \"" . $_SESSION['date3'] . "\" AND date_remise<= \"" . $_SESSION['date4'] . "\" AND numero_impaye LIKE   \"" . $_SESSION['numdossier'] . "\"  ;");
                            }
                            if ($tresorerie == null) {
                                echo "Pas de tresoreries";
                            } else {
                                while ($ligne = $tresorerie->fetch(PDO::FETCH_OBJ)) {
                                    $d = date_create($ligne->date_vente);
                                    $date = date_format($d, "d/m/Y");
                                    $d2 = date_create($ligne->date_remise);
                                    $date2 = date_format($d2, "d/m/Y");
                                    $montant = str_replace(".", ",", $ligne->montant);
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        echo "<td style=\"width:20%\">$date2</td>";
                                        echo "<td style=\"width:25%\">$ligne->NCarte</td>";
                                        echo "<td style=\"width:10%\">$ligne->reseau</td>";
                                        echo "<td style=\"width:20%\">$ligne->numero_impaye</td>";
                                        echo "<td style=\"width:20%\" class=\"negative\">$montant euros</td>";
                                        echo "<td style=\"width:20%\">$ligne->code_impaye</td>";
                                        echo "</tr>";
                                    } else {
                                        echo "<tr class=\"style2\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        echo "<td style=\"width:20%\">$date2</td>";
                                        echo "<td style=\"width:25%\">$ligne->NCarte</td>";
                                        echo "<td style=\"width:10%\">$ligne->reseau</td>";
                                        echo "<td style=\"width:20%\">$ligne->numero_impaye</td>";
                                        echo "<td style=\"width:20%\" class=\"negative\">$montant euros</td>";
                                        echo "<td style=\"width:20%\">$ligne->code_impaye</td>";
                                        echo "</tr>";
                                    }
                                    $var++;
                                }
                            }
                            $tresorerie->closeCursor();
                            ?>
                        </table>
                    </div>
                </div>

                <!-- TABLEAU, total -->
                <div class="frame">
                    <table class="frame">
                        <!-- DEFAULT -->
                        <tr class="end-row">
                            <?php
                            $requete = $cnx->query("SELECT count(numero_impaye), sum(montant) FROM impaye WHERE SIREN LIKE\"" . $_SESSION['siren3'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison3'] . "\"AND date_vente >= \"" . $_SESSION['date3'] . "\" AND date_remise<= \"" . $_SESSION['date4'] . "\" AND numero_impaye LIKE   \"" . $_SESSION['numdossier'] . "\" ");
                            $row = $requete->fetch();
                            $montant = str_replace(".", ",", $row[1]);
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:25%\">-</td>";
                            echo "<td style=\"width:10%\">-</td>";
                            echo "<td style=\"width:20%\">$row[0] dossiers</td>";
                            echo "<td style=\"width:20%\">$montant euros</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            // echo "<td style=\"width:20%\">$row[0] remises</td>";
                            // echo "<td style=\"width:20%\">-</td>";
                            // echo "<td style=\"width:25%\">$row[1] transactions</td>";
                            // echo "<td style=\"width:20%\">-</td>";
                            // echo "<td style=\"width:20%\">total = $row[2] euros</td>";
                            ?>
                        </tr>
                        <!-- Remplissage
                        <tr class="end-row">
                            <td style="width:20%;">input N° SIREN</td>
                            <td style="width:20%">input Raison Sociale</td>
                            <td style="width:25%">Total de transactions</td>
                            <td style="width:20%"></td>
                            <td style="width:20%">Montant total</td>
                        </tr>
                        -->


                    </table>
                </div>

                <!----- SOUS LE TABLEAU ----->
                <div class="frame row-space-between" style="margin-top: 2px;">

                    <!-- TRI -->
                    <form method="POST" action="impayespo.php">
                        <select name="filtre">
                            <option selected disabled hidden>--</option>
                            <option value="SIREN"> SIREN</option>
                            <option value="raison_sociale">Raison Sociale</option>
                            <option value="date_vente">Date de vente</option>
                            <option value="date_remise">Date de remise</option>
                            <option value="reseau">Reseau</option>
                            <option value="numero_impaye">Numéro de dossier</option>
                            <option value="montant">Montant total</option>
                            <option value="code_impaye">Code d'impayé</option>
                        </select>
                        <select name="croissance">
                            <option selected disabled hidden>--</option>
                            <option value="asc">ordre croissant</option>
                            <option value="desc">ordre décroissant</option>
                        </select>
                        <button type="submit">Trier</button>
                        <button name="resets" value="resets">Rénitialiser</button>
                    </form>


                    <!-- EXPORT -->
                    <form class="table-export" onsubmit="return false">
                        <p>Format du tableau :</p>
                        <select name="table-format" class="table-format">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                            <option value="xls">XLS</option>
                        </select>
                        <button onclick="exporter('po-par-client-display')">Exporter</button>
                    </form>
                </div>
            </div>

        </section>

    </section>
    <script src="../exports.js"></script>


    <!-- FOOTER -->
    <footer>

        <script>
            //Option : tous-clients / par-client
            function displayTable(optionId, displayId) {
                //remove checked from all options
                const allOptionsRadio = document.querySelectorAll(".option-radio");
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