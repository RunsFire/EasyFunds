<!DOCTYPE html>
<?php
	session_start ();
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    if (!isset($_SESSION['siren'])){
        $_SESSION['siren']= "%";
        $_SESSION['raison']= "%";
        $_SESSION['date']= "%";      
    }
    // if($_SESSION['typeu']!=2 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
	// 	header('location:login.php');
	// }
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Accueil</title>
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

            <!-- OPTIONS, tous clients / par client -->
            <div class="frame options">

                <!-- TRÉSORERIE DES COMPTES CLIENTS -->
                <a href="" class="option active">Trésorerie des comptes clients</a>

                <!-- TRÉSORERIE PAR COMPTE CLIENT -->
                <a href="po_par_compte_client.php" class="option">Trésorerie par compte clients</a>

            </div>
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- PO TOUS CLIENTS -->
            <div id="po-tous-clients" class="display active">

                <!-- FILTRES -->
                <div class="frame filtres">
                    <form method="GET" action="po_des_compte_client.php">
                        <?php
                            if (!empty($_GET['siren'])){
                                echo '<input type="text" name="siren" class="filtre" value='.$_GET['siren'].' placeholder='.$_GET['siren'].'>';
                            }else{
                                echo '<input type="text" name="siren" class="filtre" placeholder="N° SIREN">';
                            }if (!empty($_GET['raison'])){
                                echo '<input type="text" name="raison" class="filtre" value='.$_GET['raison'].' placeholder='.$_GET['raison'].'>';
                            }else{
                                echo '<input type="text" name="raison" class="filtre" placeholder="Raison sociale">';
                            }if (!empty($_GET['date'])){
                                echo '<input type="date" name="date" class="filtre" value='.$_GET['date'].'>';
                            }else{
                                echo '<input type="date" name="date" class="filtre">';
                            }
                        ?>
                        <button type="submit" class="search">Rechercher</button>
                        <button name="reset" value="reset" class="search">Rénitialiser</button>
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
                                <th style="width:20%">Date</th>
                                <th style="width:20%">Montant total</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas">
                        <table class="frame">
                            <?php
                                include("connexion.inc.php");
                                $var = 0;
                                if (!empty($_GET['siren'])){
                                    $_SESSION['siren'] =$_GET['siren']."%";
                                } if (!empty($_GET['raison']) ){
                                    $_SESSION['raison'] = "%".$_GET['raison']."%";
                                } if (!empty($_GET['date'])){
                                    $_SESSION['date']= "%".$_GET['date']."%";
                                } if (!empty($_GET['reset'])){
                                    $_SESSION['siren']= "%";
                                    $_SESSION['raison']= "%";
                                    $_SESSION['date']= "%";      
                                    unset($_GET['reset']);
                                }if (!empty($_GET['resets'])){
                                    unset( $_SESSION['filtre']);
                                    unset( $_SESSION['croissance']);
                                    unset($_GET['filtre']);
                                    unset($_GET['croissance']);
                                    unset($_GET['resets']);
                                }
                                if (!empty($_GET['filtre']) && !empty($_GET['croissance']) ){
                                    $_SESSION['filtre']=$_GET['filtre'];
                                    $_SESSION['croissance']=$_GET['croissance'];
                                } if (isset($_SESSION['filtre']) &&  isset($_SESSION['croissance'])){
                                    $tresorerie = $cnx-> query("SELECT SIREN,raison_sociale,nombre_transactions,date,montant_total FROM tresorerie WHERE SIREN LIKE \"".$_SESSION['siren']."\" AND raison_sociale  LIKE \"".$_SESSION['raison']."\" AND date LIKE \"".$_SESSION['date']."\" ORDER BY ". $_SESSION['filtre']." ". $_SESSION['croissance'].";");
                                }else{
                                    $tresorerie = $cnx-> query("SELECT SIREN,raison_sociale,nombre_transactions,date,montant_total FROM tresorerie WHERE SIREN LIKE\"".$_SESSION['siren']."\" AND raison_sociale  LIKE \"".$_SESSION['raison']."\" AND date LIKE\"".$_SESSION['date']."\" ;");
                                }
                                if ($tresorerie==null){
                                    echo "Pas de tresoreries";
                                }else {
                                    while( $ligne = $tresorerie->fetch(PDO::FETCH_OBJ)){ 
                                        $d=date_create($ligne->date);
                                        $date = date_format($d,"d/m/Y");
                                        if ($var%2==0){
                                            echo "<tr class=\"style1\">";
                                            echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                            echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                            echo "<td style=\"width:25%\">$ligne->nombre_transactions</td>";
                                            echo "<td style=\"width:20%\">$date</td>";
                                            echo "<td style=\"width:20%\">$ligne->montant_total euros</td>";
                                            echo "</tr>";
                                        }else{
                                            echo "<tr class=\"style2\">";
                                            echo "<td style=\"width:20%\">$ligne->SIREN</td>";
                                            echo "<td style=\"width:20%\">$ligne->raison_sociale</td>";
                                            echo "<td style=\"width:25%\">$ligne->nombre_transactions</td>";
                                            echo "<td style=\"width:20%\">$date</td>";
                                            echo "<td style=\"width:20%\">$ligne->montant_total euros</td>";
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

                <!----- SOUS LE TABLEAU ----->
                <div class="frame row-space-between" style="margin-top: 2px;">

                    <!-- TRI -->
                    <form method="GET" action="po_des_compte_client.php">
                        <select name="filtre">
                            <option selected disabled hidden>--</option>
                            <option value="SIREN">N° SIREN</option>
                            <option value="raison_sociale">Raison Sociale</option>
                            <option value="nombre_transactions">Nb de transactions</option>
                            <option value="date">Date</option>
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


                    <!----- EXPORT ----->
                    <form class="frame table-export" onsubmit="return false">
                        <p>Format du tableau :</p>
                        <select name="table-format" class="table-format" id="formatchoisi">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                            <option value="xls">XLS</option>
                        </select>
                        <button onclick="exporter('po-tous-clients')" class="export">Exporter</button>
                    </form>

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