<?php
session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '2') {
    header('location:login.php');
}
if (!isset($_SESSION['siren'])) {
    $_SESSION['siren'] = "%";
    $_SESSION['raison'] = "%";
    $_SESSION['date'] = "%";
}
if (isset($_GET['remise'])) {
    $_SESSION['codeRemise'] = $_GET['remise'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Détails de la remise </title>
    <link rel="icon" href="easyfunds-icon.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="exports.js"></script>
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
        <a class="tab" href="tresoreriepo.php">Trésorerie</a>
        <a class="tab active" href="detailpo.php">Remises</a>
        <a class="tab" href="impayespo.php">Impayés</a>
        <a class="tab" href="demandepo.php">Demandes</a>
    </div>
</header>

<body>
    <section class="container">

        <!-- SECTION ABOVE TABLE -->
        <section>
            <!-- Retour -->
            <div class="frame">
                <a href="remisespo.php"><img class="return"
                        src="https://www.pngkit.com/png/full/24-244890_left-arrow-curved-black-symbol-comments-turn-page.png"></a>
            </div>

            <!-- BONJOUR [UTILISATEUR] -->
            <div class="frame greet-user ">
                <?php echo "<p>Bonjour <span class=\"username\" style=\"color:white\">" . $_SESSION['pseudo'] . "</span></p>" ?>
                <a class="disconnect" href="">Se déconnecter</a>
            </div>

            <!-- Onglet, Transaction de la remise -->
            <div class="frame options">
                <a class="option active no-action">Transactions de la remise</a>
            </div>
        </section>

        <!-- DISPLAY TABLE, transactions-->
        <section class="table-display">

            <!-- Entête du tableau, Remise -->
            <div class="frame">
                <?php echo "<p class=\"info\">Remise " . $_SESSION['codeRemise'] . "</p>" ?>
            </div>

            <!-- detail de remise -->
            <div id="po-par-client-display" class="display active">

                <!----- TABLEAU, headers + datas ----->
                <div class="table frame">
                    <!-- TABLEAU HEADERS-->
                    <div class="frame table-headers">
                        <table class="frame">
                            <tr>
                                <th style="width:20%">SIREN</th>
                                <th style="width:20%">Date de vente</th>
                                <th style="width:25%">numéro de Carte</th>
                                <th style="width:20%">Réseau</th>
                                <th style="width:20%">Autorisation</th>
                                <th style="width:20%">Montant</th>
                            </tr>
                        </table>
                    </div>

                    <!-- TABLEAU DATAS -->
                    <div class="table-datas">
                        <table class="frame">
                            <?php
                            include("connexion.inc.php");
                            $var = 0;
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
                                $detail = $cnx->query("SELECT SIREN,date_vente,nCarte,reseau,num_detail,montant FROM tableau_details WHERE numero_remise=" . $_SESSION['codeRemise'] . " ORDER BY " . $_SESSION['filtre'] . " " . $_SESSION['croissance'] . ";");
                            } else {
                                $detail = $cnx->query("SELECT SIREN,date_vente,nCarte,reseau,num_detail,montant FROM tableau_details WHERE numero_remise=" . $_SESSION['codeRemise'] . ";");
                            }
                            echo "----" . $detail->rowCount();
                            if ($detail->rowCount() > 0) {
                                while ($ligne = $detail->fetch(PDO::FETCH_OBJ)) {
                                    $d = date_create(datetime: $ligne->date_vente);
                                    $date = date_format($d, "d/m/Y");
                                    if ($var % 2 == 0) {
                                        echo "<tr class=\"style1\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        echo "<td style=\"width:25%\">$ligne->nCarte</td>";
                                        echo "<td style=\"width:20%\">$ligne->reseau</td>";
                                        echo "<td style=\"width:20%\">$ligne->num_detail</td>";
                                        if ($ligne->montant < 0) {
                                            echo "<td style=\"width:20%\" class=\"negative\">$ligne->montant euros</td>";
                                        } else {
                                            echo "<td style=\"width:20%\">$ligne->montant euros</td>";
                                        }
                                        echo "</tr>";
                                    } else {
                                        echo "<tr class=\"style2\">";
                                        echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                        echo "<td style=\"width:20%\">$date</td>";
                                        echo "<td style=\"width:25%\">$ligne->nCarte</td>";
                                        echo "<td style=\"width:20%\">$ligne->reseau</td>";
                                        echo "<td style=\"width:20%\">$ligne->num_detail</td>";
                                        if ($ligne->montant < 0) {
                                            echo "<td style=\"width:20%\" class=\"negative\">$ligne->montant euros</td>";
                                        } else {
                                            echo "<td style=\"width:20%\">$ligne->montant euros</td>";
                                        }
                                        echo "</tr>";
                                    }
                                    $var++;
                                }
                                $detail->closeCursor();
                            }
                            ?>
                            <!-- TEMPLATE DONNÉES
                            <tr class="style1">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Date de traitement</td>
                            <td style="width:20%">Nb de transactions</td>
                            <td style="width:20%" class="negative">Montant negatif</td>
                        </tr>
                        <tr class="style2">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Date de traitement</td>
                            <td style="width:20%">Nb de transactions</td>
                            <td style="width:20%">Montant positif</td>
                        </tr>
                        -->
                        </table>
                    </div>
                </div>


                <!----- SOUS LE TABLEAU ----->
                <div class="frame row-space-between" style="margin-top: 2px;">
                    <!-- TRI -->
                    <form method="POST" action="detailpo.php">
                        <select name="filtre">
                            <option selected disabled hidden>--</option>
                            <option value="SIREN">N° SIREN</option>
                            <option value="date_vente">Date</option>
                            <option value="reseau">Reseau</option>
                            <option value="montant">Montant</option>
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
</body>

</html>