<?php
session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '2') {
    header('location:../login.php');
}
if ($_SESSION['typeu'] != 2 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:../login.php');
}
include("../connexion.inc.php");
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../page.css">
    <meta charset="utf-8">
    <title>Remises des clients</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/jspdf/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <link rel="icon" type="image/png" href="/img/easyfunds-icon.png">
</head>

<!-- HEADER -->
<header>
    <!-- ICON -->
    <div class="logo">
        <img src="/img/easyfunds-icon.png" class="small-icon">
        <img src="/img/easyfund-logo.png" class="small-logo">
    </div>

    <!-- ONGLETS -->
    <div class="tabs">
        <a class="tab" href="tresoreriepo.php">Trésorerie</a>
        <a class="tab active" href="remisespo.php">Remises</a>
        <a class="tab" href="impayespo.php">Impayés</a>
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
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- FILTRES -->
            <div class="frame filtres">
                <form method="POST" action="remisespo.php">
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
                    ?>
                    <button type="submit" class="search">Rechercher</button>
                    <button name="reset" value="reset" class="search">Rénitialiser</button>
                </form>
            </div>

            <!-- PO PAR CLIENT, vide par défaut -->
            <div id="po-par-client-display" class="display active">

                <!----- TABLEAU, headers + datas ----->
                <div class="table frame" id="po-tous-clients">
                    <!-- TABLEAU HEADERS-->
                    <div class="frame table-headers">
                        <table class="frame">
                            <tr>
                                <th style="width:20%">SIREN</th>
                                <th style="width:20%">Raison Sociale</th>
                                <th style="width:25%">Nb de transactions</th>
                                <th style="width:20%">Date</th>
                                <th style="width:20%">Montant total</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas shorter-table" id="po-par-client">
                        <table class="frame">
                            <?php
                            $var = 0;
                            if (!empty($_POST['siren'])) {
                                $_SESSION['siren'] = $_POST['siren'] . "%";
                            }
                            if (!empty($_POST['raison'])) {
                                $_SESSION['raison'] = "%" . $_POST['raison'] . "%";
                            }
                            if (!empty($_POST['date'])) {
                                $_SESSION['date'] = "%" . $_POST['date'] . "%";
                            }
                            if (!empty($_POST['reset'])) {
                                $_SESSION['siren'] = "%";
                                $_SESSION['raison'] = "%";
                                $_SESSION['date'] = "%";
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
                                $tresorerie = $cnx->query("SELECT numero_remise, SIREN,raison_sociale,nbre_transaction,date_traitement,montant_total FROM remise WHERE SIREN LIKE \"" . $_SESSION['siren'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison'] . "\" AND date_traitement LIKE \"" . $_SESSION['date'] . "\" ORDER BY " . $_SESSION['filtre'] . " " . $_SESSION['croissance'] . ";");
                            } else {
                                $tresorerie = $cnx->query("SELECT numero_remise, SIREN,raison_sociale,nbre_transaction,date_traitement,montant_total FROM remise WHERE SIREN LIKE\"" . $_SESSION['siren'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison'] . "\" AND date_traitement LIKE\"" . $_SESSION['date'] . "\" ;");
                            }
                            if ($tresorerie == null) {
                                echo "Pas de tresoreries";
                            } else {
                                while ($ligne = $tresorerie->fetch(PDO::FETCH_OBJ)) {
                                    $d = date_create($ligne->date_traitement);
                                    $date = date_format($d, "d/m/Y");
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\" onclick=\"window.location.href='detailpo.php?remise=$ligne->numero_remise'\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                        echo "<td style=\"width:25%\">$ligne->nbre_transaction</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        if ($ligne->montant_total < 0) {
                                            echo "<td style=\"width:20%\" class=\"negative\">$ligne->montant_total euros</td>";
                                        } else {
                                            echo "<td style=\"width:20%\">$ligne->montant_total euros</td>";
                                        }
                                        echo "</tr>";
                                    } else {
                                        echo "<tr class=\"style2\" onclick=\"window.location.href='detailpo.php?remise=$ligne->numero_remise'\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                        echo "<td style=\"width:25%\">$ligne->nbre_transaction</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        if ($ligne->montant_total < 0) {
                                            echo "<td style=\"width:20%\" class=\"negative\">$ligne->montant_total euros</td>";
                                        } else {
                                            echo "<td style=\"width:20%\">$ligne->montant_total euros</td>";
                                        }
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
                            $requete = $cnx->query("SELECT count(numero_remise), sum(nbre_transaction), sum(montant_total) FROM remise");
                            $row = $requete->fetch();
                            $montant = str_replace(".", ",", $row[2]);
                            echo "<td style=\"width:20%\">$row[0] remises</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:25%\">$row[1] transactions</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:20%\">total = $montant euros</td>";
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
            </div>


            <!----- SOUS LE TABLEAU ----->
            <div class="frame row-space-between" style="margin-top: 2px;">

                <!-- TRI -->
                <form method="POST" action="remisespo.php">
                    <select name="filtre">
                        <option selected disabled hidden>--</option>
                        <option value="SIREN"> SIREN</option>
                        <option value="raison_sociale">Raison Sociale</option>
                        <option value="nbre_transaction">Nb de transactions</option>
                        <option value="date_traitement">Date</option>
                        <option value="montant_total">Montant total</option>
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