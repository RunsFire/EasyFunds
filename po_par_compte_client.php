<!DOCTYPE html>
<?php
	session_start ();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    // if($_SESSION['typeu']!=2 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
	// 	header('location:login.php');
	// }
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Accueil</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/jspdf/dist/jspdf.umd.min.js"></script>
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
        <a class="tab active" href="">Trésorerie</a>
        <a class="tab" href="">Remises</a>
        <a class="tab" href="">Impayés</a>
        <a class="tab" href="">Graphiques</a>
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

            <div class="frame options">

                <!-- TRÉSORERIE DES COMPTES CLIENTS -->
                <a href="po_des_compte_client.php" class="option">Trésorerie des comptes clients</a>

                <!-- TRÉSORERIE PAR COMPTE CLIENT -->
                <a href="" class="option active">Trésorerie par compte clients</a>

            </div>
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- PO PAR CLIENT, vide par défaut -->
            <div id="po-par-client-display" class="display active">
                <!-- FILTRES -->
                <div class="frame filtres" class="">
                    <form action="nomDuFichier.php?display='po-par-client'" method="get">
                        <input type="text" class="filtre" placeholder="N° SIREN du client">
                        <input type="text" class="filtre" placeholder="Raison sociale du client">
                        <input type="date" class="filtre">
                        <button type="submit">Rechercher</button>
                    </form>
                </div>

                <!----- TABLEAU, headers + datas ----->
                <div class="table frame" id="po-tous-clients">
                    <!-- TABLEAU HEADERS-->
                    <div class="frame table-headers">
                        <table class="frame">
                            <tr>
                                <th style="width:20%">N° SIREN</th>
                                <th style="width:20%">Raison Sociale</th>
                                <th style="width:25%">Nb de transactions</th>
                                <th style="width:20%">jj/mm/aaaa</th>
                                <th style="width:20%">Montant total</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas shorter-table" id="po-par-client">
                        <table class="frame">
                            <!-- TEMPLATE DONNÉES

                                [!] REMPLACER codeRemise par le code de le remise

                                <tr class="style1" onclick="window.location.href='po_transactions.html?remise=codeRemise'">
                                    <td style="width:20%">N° SIREN</td>
                                    <td style="width:20%">Raison Sociale</td>
                                    <td style="width:25%">Nb de transactions</td>
                                    <td style="width:20%">Date</td>
                                    <td style="width:20%">Montant total</td>
                                </tr>
                                <tr class="style2" onclick="window.location.href='po_transactions.html?remise=codeRemise'">
                                    <td style="width:20%">N° SIREN</td>
                                    <td style="width:20%">Raison Sociale</td>
                                    <td style="width:25%">Nb de transactions</td>
                                    <td style="width:20%">Date</td>
                                    <td style="width:20%">Montant total</td>
                                </tr>
                            -->
                            <tr class="style1" onclick="window.location.href='po_transactions.html?remise=codeRemise'">
                                <td style="width:20%">N° SIREN</td>
                                <td style="width:20%">Raison Sociale</td>
                                <td style="width:25%">Nb de transactions</td>
                                <td style="width:20%">Date</td>
                                <td style="width:20%">Montant</td>
                            </tr>
                            <tr class="style2" onclick="window.location.href='po_transactions.html?remise=codeRemise'">
                                <td style="width:20%">N° SIREN</td>
                                <td style="width:20%">Raison Sociale</td>
                                <td style="width:25%">Nb de transactions</td>
                                <td style="width:20%">Date</td>
                                <td style="width:20%">Montant</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- TABLEAU, total -->
                <div class="frame">
                    <table class="frame">
                        <!-- DEFAULT -->
                        <tr class="end-row">
                            <td style="width:20%;">-</td>
                            <td style="width:20%">-</td>
                            <td style="width:25%">-</td>
                            <td style="width:20%"></td>
                            <td style="width:20%">-
                            <td>
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
                    <form>
                        <select name="filtre">
                            <option selected disabled hidden>--</option>
                            <option value="siren">N° SIREN</option>
                            <option value="raison">Raison Sociale</option>
                            <option value="nb-tr">Nb de transactions</option>
                            <option value="date">Date</option>
                            <option value="mt-total">Montant total</option>
                        </select>
                        <select name="croissance">
                            <option selected disabled hidden>--</option>
                            <option value="asc">ordre croissant</option>
                            <option value="desc">ordre décroissant</option>
                        </select>
                        <button type="submit">Trier</button>
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