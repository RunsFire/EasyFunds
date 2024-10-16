<?php
	session_start ();
    // if($_SESSION['typeu']!=2 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
	// 	header('location:login.php');
	// }
    include("connexion.inc.php");
    $results = $cnx->query("SELECT * from \"easyfunds\".\"tresorerie\" ORDER BY num_tresorerie DESC");
    $data = $results->fetchAll();
    $results->closeCursor();
?>

<!DOCTYPE html>

<html>


<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Text</title>
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
        <a class="tab active" href="">Trésorerie</a>
        <a class="tab" href="">Remises</a>
        <a class="tab" href="">Impayés</a>
        <a class="tab" href="">Graphiques</a>
    </div>
</header>

<section class="container">

    <!-- SECTION ABOVE TABLE-DISPLAY -->
    <section>
        <!-- BONJOUR [UTILISATEUR] -->
        <div class="frame greet-user ">
            <p>Bonjour <span class="username" style="color:white">[user]</span></p>
            <a class="disconnect" href="">Se déconnecter</a>
        </div>

        <!-- OPTIONS, tous clients / par client -->
        <div class="frame options">

            <!-- TRÉSORERIE DES COMPTES CLIENTS -->
            <input href=#po-tous-clients type="radio" class="option-radio" name="option" id="tous-clients" checked>
            <label for="tous-clients" class="option-label"
                onclick="displayTable('tous-clients', 'po-tous-clients')">Trésorerie des comptes clients</label>

            <!-- TRÉSORERIE PAR COMPTE CLIENT -->
            <input type="radio" class="option-radio" name="option" id="par-client">
            <label for="par-client" class="option-label"
                onclick="displayTable('par-client', 'po-par-client')">Trésorerie par compte client</label>

        </div>
    </section>


    <!-- DISPLAY TABLES + DATAS -->
    <section class="table-display">

        <!-- PO TOUS CLIENTS -->
        <div id="po-tous-clients" class="display active">

            <!-- FILTRES -->
            <div class="frame filtres">
                <form>
                    <input type="text" class="filtre" placeholder="N° SIREN">
                    <input type="text" class="filtre" placeholder="Raison sociale">
                    <input type="date" class="filtre">
                    <button type="submit" class="search">Rechercher</button>
                </form>
            </div>

            <!----- TABLEAU, headers + datas ----->
            <div class="table frame">
                <!-- TABLEAU HEADERS-->
                <div class="frame table-headers">
                    <table class="frame">
                        <tr>
                            <th style="width:20%">N° SIREN</th>
                            <th style="width:20%">Raison Sociale</th>
                            <th style="width:25%">Nb de transactions</th>
                            <th style="width:20%">Devise (EUR)</th>
                            <th style="width:20%">Montant total</th>
                        </tr>
                    </table>
                </div>
                <!-- TABLEAU DATAS -->
                <div class="table-datas">
                    <table class="frame">
                        <?php
                        $compteur = 1;
                        // il faut remplirla base pour savoir si sa fonctionne...
                        foreach ($data as $rows) 
                        {
                            $style = "style1";
                            if ($compteur%2 ==0 ){
                                $style ="style2";
                            }
                            $compteur++;
                            ?>
                        <tr class=<?php $style ?>>
                            <td style="width:20%"><?php $rows["siren"] ?></td>
                            <td style="width:20%"><?php $rows["raison_sociale"] ?></td>
                            <td style="width:25%"><?php $rows["nombre_transactions"] ?></td>
                            <td style="width:20%"><?php $rows["devise_"] ?></td>
                            <td style="width:20%"><?php $rows["montant_total"] ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr class="style1">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Nb de transactions</td>
                            <td style="width:20%">Devise (EUR)</td>
                            <td style="width:20%">Montant total</td>
                        </tr>
                        <tr class="style2">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Nb de transactions</td>
                            <td style="width:20%">Devise (EUR)</td>
                            <td style="width:20%">Montant total</td>
                        </tr>

                    </table>
                </div>
            </div>

            <!----- EXPORT ----->
            <form class="frame table-export" onsubmit="return false">
                <p>Format du tableau :</p>
                <select name="table-format" class="table-format">
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                    <option value="xls">XLS</option>
                </select>
                <button onclik="exportTableToPdf('')" class="export">Exporter</button>
            </form>

        </div>

        <!-- PO PAR CLIENT -->
        <div id="po-par-client" class="display">
            <!-- FILTRES -->
            <div class="frame filtres" class="">
                <form>
                    <input type="text" class="filtre" placeholder="N° SIREN du client">
                    <input type="text" class="filtre" placeholder="Raison sociale du client">
                    <input type="date" class="filtre">
                    <button type="submit" class="search">Rechercher</button>
                </form>
            </div>

            <!----- TABLEAU, headers + datas ----->
            <div class="table frame">
                <!-- TABLEAU HEADERS-->
                <div class="frame table-headers">
                    <table class="frame">
                        <tr>
                            <th style="width:20%">N° SIREN</th>
                            <th style="width:20%">Raison Sociale</th>
                            <th style="width:25%">Nb de transactions</th>
                            <th style="width:20%">Devise (EUR)</th>
                            <th style="width:20%">Montant total</th>
                        </tr>
                    </table>
                </div>
                <!-- TABLEAU DATAS -->
                <div class="table-datas">
                    <table class="frame">
                        <tr class="style1">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Nb de transactions</td>
                            <td style="width:20%">Devise (EUR)</td>
                            <td style="width:20%">Montant total</td>
                        </tr>
                        <tr class="style2">
                            <td style="width:20%">N° SIREN</td>
                            <td style="width:20%">Raison Sociale</td>
                            <td style="width:25%">Nb de transactions</td>
                            <td style="width:20%">Devise (EUR)</td>
                            <td style="width:20%">Montant total</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!----- EXPORT ----->
            <form class="frame table-export" onsubmit="return false">
                <p>Format du tableau :</p>
                <select name="table-format" class="table-format">
                    <option value="csv">CSV</option>
                    <option value="pdf">PDF</option>
                    <option value="xls">XLS</option>
                </select>
                <button onclick="exportTableToPdf('po-par-client')" class="export">Exporter</button>
            </form>
        </div>

    </section>

</section>

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

</html>