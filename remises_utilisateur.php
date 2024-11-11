<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '0') {
    header('location:login.php');
}
if (!isset($_SESSION['siren2'])) {
    $_SESSION['siren2'] = "%";
    $_SESSION['raison2'] = "%";
    $_SESSION['date2'] = "%";
}
$num = $_SESSION['num'];
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Remises</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
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
        <a class="tab" href="tresorerie_utilisateur.php">Trésorerie</a>
        <a class="tab active" href="remises_utilisateur.php">Remises</a>
        <a class="tab" href="impayes_utilisateur.php">Impayés</a>
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

            <!-- PO PAR CLIENT, vide par défaut -->
            <div id="po-par-client-display" class="display active">
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
                            include("connexion.inc.php");
                            $var = 0;
                            if (!empty($_POST['siren'])) {
                                $_SESSION['siren2'] = $_POST['siren'] . "%";
                            }
                            if (!empty($_POST['raison'])) {
                                $_SESSION['raison2'] = "%" . $_POST['raison'] . "%";
                            }
                            if (!empty($_POST['date'])) {
                                $_SESSION['date2'] = "%" . $_POST['date'] . "%";
                            }
                            if (!empty($_POST['reset'])) {
                                $_SESSION['siren2'] = "%";
                                $_SESSION['raison2'] = "%";
                                $_SESSION['date2'] = "%";
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
                                $_SESSION['filtre2'] = $_POST['filtre'];
                                $_SESSION['croissance2'] = $_POST['croissance'];
                            }
                            if (isset($_SESSION['filtre2']) &&  isset($_SESSION['croissance2'])) {
                                $tresorerie = $cnx->query("SELECT numero_remise, SIREN,raison_sociale,nbre_transaction,date_traitement,montant_total FROM remise WHERE num_utilisateur= $num AND SIREN LIKE \"" . $_SESSION['siren2'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison2'] . "\" AND date_traitement LIKE \"" . $_SESSION['date2'] . "\" ORDER BY " . $_SESSION['filtre2'] . " " . $_SESSION['croissance2'] . ";");
                            } else {
                                $tresorerie = $cnx->query("SELECT numero_remise, SIREN,raison_sociale,nbre_transaction,date_traitement,montant_total FROM remise WHERE num_utilisateur= $num AND SIREN LIKE\"" . $_SESSION['siren2'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison2'] . "\" AND date_traitement LIKE\"" . $_SESSION['date2'] . "\" ;");
                            }
                            if ($tresorerie == null) {
                                echo "Pas de tresoreries";
                            } else {
                                while ($ligne = $tresorerie->fetch(PDO::FETCH_OBJ)) {
                                    $d = date_create($ligne->date_traitement);
                                    $date = date_format($d, "d/m/Y");
                                    $montant = str_replace(".", ",", $ligne->montant_total);
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\"";
                                    } else {
                                        echo "<tr class=\"style2\"";
                                    }
                                    echo "onclick=\"window.location.href='detailpo.php?remise=$ligne->numero_remise'\">";
                                    echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                    echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                    echo "<td style=\"width:25%\">$ligne->nbre_transaction</td>";
                                    echo "<td style=\"width:20%\">$date</td>";
                                    if ($ligne->montant_total < 0) {
                                        echo "<td style=\"width:20%\" class=\"negative\">$montant euros</td>";
                                    } else {
                                        echo "<td style=\"width:20%\">$montant euros</td>";
                                    }
                                    echo "</tr>";
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
                            include("connexion.inc.php");
                            $requete = $cnx->query("SELECT count(numero_remise), sum(nbre_transaction), sum(montant_total) FROM remise WHERE num_utilisateur= $num AND SIREN LIKE\"" . $_SESSION['siren2'] . "\" AND raison_sociale  LIKE \"" . $_SESSION['raison2'] . "\" AND date_traitement LIKE\"" . $_SESSION['date2'] . "\" ");
                            $row = $requete->fetch();
                            $montant = str_replace(".", ",", 0 + $row[2]);
                            echo "<td style=\"width:20%\">" . $row[0] . " remises</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:25%\">" . 0 + $row[1] . " transactions</td>";
                            echo "<td style=\"width:20%\">-</td>";
                            echo "<td style=\"width:20%\">total = $montant euros</td>";
                            ?>
                        </tr>
                    </table>
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
            </div>

        </section>

    </section>
    <script src="exports.js"></script>


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