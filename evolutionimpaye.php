<!DOCTYPE html>
<?php
	session_start ();
    if (!isset( $_SESSION['typeu']) || $_SESSION['typeu']!='2'){
        header('location:login.php');
    }     
    include("connexion.inc.php");
    $date = date_create(date('Y-m-d'));
    if (!isset($_POST["choix"]) || (isset($_POST['choix']) && $_POST['choix']=='4')){
        $date2 = date_sub($date,date_interval_create_from_date_string('3 month'));
    } else if (isset($_POST['choix']) && $_POST['choix']=='12'){
        $date2= date_sub($date,date_interval_create_from_date_string('1 year'));
    }   
    $date3 = $date2->format("Y-m-d");
    $lstmois = [];
    $liste_mois_fr = array("01" => "Janvier" , "02" => "Février" , "03" => "Mars" , "04" => "Avril" , "05" => "Mai" , "06" => "Juin" , "07" => "Juillet" , "08" => "Août" , "09" => "Septembre" , "10" => "Octobre" , "11" => "Novembre" , "12" => "Décembre");
    $donnees = [];
    $compteur=0;
    if (isset($_POST['choix'])){
        while ($compteur < $_POST['choix']){
            array_push($lstmois,$liste_mois_fr[$date2->format('m')]." ".$date2->format("Y"));
            $donnees[$liste_mois_fr[$date2->format('m')]]=0;
            $date2 = date_add($date,date_interval_create_from_date_string('1 month'));
            $compteur++;
        }
        $requete = $cnx->query("SELECT date_vente FROM impaye WHERE date_vente>='$date3';");
        while( $ligne = $requete->fetch(PDO::FETCH_OBJ)){
            $date4 = date_create($ligne->date_vente);
            $donnees[$liste_mois_fr[$date4->format('m')]]++;
        }
        $requete->closeCursor();
        $mois = [];
        $regex = '/^[A-Za-zéèêëîïôöûüàâäç]+/';
        foreach ($lstmois as $dateString) {
            if (preg_match($regex, $dateString, $matches)) {
                $mois[] = $matches[0];
            }
        }
    }
    
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Evolution des impayés</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/jspdf/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
                <?php echo "<p>Bonjour <span class=\"username\" style=\"color:white\">".$_SESSION['pseudo']."</span></p>" ?>
                <a class="disconnect" href="deconnexion.php">Se déconnecter</a>
            </div>

            <div class="frame options">

                <!-- LISTE IMPAYES DES COMPTES CLIENTS -->
                <a href="impayespo.php" class="option">Liste</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="graphimpayespo.php" class="option">Somme des impayés</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="evolutionimpaye.php" class="option active">Évolution des impayés</a>

            </div>
        </section>
        <form method="POST" action="evolutionimpaye.php">
            <?php
            if ($_POST['choix']=='4'){
                echo '<input type="radio" id="quatre" name="choix" value="4" checked /> les 4 derniers mois';
            }else {
                echo '<input type="radio" id="quatre" name="choix" value="4"/> les 4 derniers mois';
            }if ($_POST['choix']=='12'){
                echo '<input type="radio" id="six" name="choix" value="12" checked /> les 12 derniers mois'; 
            }else {
                echo '<input type="radio" id="six" name="choix" value="12" /> les 12 derniers mois'; 
            } if ($_POST['choix']=='date'){
                echo '<input type="radio" id="date" name="choix" value="date" checked /> entre 2 dates<br>';
            } else {
                echo '<input type="radio" id="date" name="choix" value="date"  /> entre 2 dates<br>';
            }
            ?>
            <button type="submit">Choisir</button>
        </form>
        <div style="width:70%;">
        <canvas id="myChart">
        <script>
            <?php
                if ( $_POST['choix'] == 4 ){
                    $d1 =  $donnees[$mois[0]];
                    $d2 =  $donnees[$mois[1]];
                    $d3 =  $donnees[$mois[2]];
                    $d4 =  $donnees[$mois[3]];
                    echo "var lstMois = [\"$lstmois[0]\",\"$lstmois[1]\",\"$lstmois[2]\",\"$lstmois[3]\"];";
                    echo "var donnees = [$d1,$d2,$d3,$d4];";
                } else if ( $_POST['choix'] == '12'){
                    $d1 =  $donnees[$mois[0]];
                    $d2 =  $donnees[$mois[1]];
                    $d3 =  $donnees[$mois[2]];
                    $d4 =  $donnees[$mois[3]];
                    $d5 =  $donnees[$mois[4]];
                    $d6 =  $donnees[$mois[5]];
                    $d7 =  $donnees[$mois[6]];
                    $d8 =  $donnees[$mois[7]];
                    $d9 =  $donnees[$mois[8]];
                    $d10 =  $donnees[$mois[9]];
                    $d11 =  $donnees[$mois[10]];
                    $d12 =  $donnees[$mois[11]];
                    echo "var lstMois = [\"$lstmois[0]\",\"$lstmois[1]\",\"$lstmois[2]\",\"$lstmois[3]\",\"$lstmois[4]\",\"$lstmois[5]\",\"$lstmois[6]\",\"$lstmois[7]\",\"$lstmois[8]\",\"$lstmois[9]\",\"$lstmois[10]\",\"$lstmois[11]\"];";
                    echo "var donnees = [$d1,$d2,$d3,$d4,$d5,$d6,$d7,$d8,$d9,$d10,$d11,$d12];";
                }
            ?>
            var barColors = ["#9E00FF","#7823AC","#9357B8","#593A6D","#C264FC","#8B12D6","#9E00FF","#995AC1","#A347E0","#6A1E88","#B864EA","#7D38B2"];
            new Chart("myChart", {
            type: "bar",
            data: {
                labels: lstMois,
                datasets: [{
                    backgroundColor: barColors,
                    label: 'Évolution des impayés',
                    data: donnees,
                }
                ]
            },
            options: {
                title: {
                    display: false,
                    text: "Somme des impayés (en euros)"
                }
            }
            });
        </script>
        </canvas>
</div>

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