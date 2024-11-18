<!DOCTYPE html>
<?php
session_start();
if (!isset($_SESSION['typeu']) || $_SESSION['typeu'] != '2') {
    header('location:../login.php');
}
include("../connexion.inc.php");
$u = $cnx->query("SELECT ABS(sum(montant)) FROM impaye WHERE code_impaye='01';");
$un = $u->fetch();
$d = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='02';");
$deux = $d->fetch();
$t = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='03';");
$trois = $t->fetch();
$q = $cnx->query("SELECT ABS(sum(montant))   FROM impaye WHERE code_impaye='04';");
$quatre = $q->fetch();
$c = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='05';");
$cinq = $c->fetch();
$s = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='06';");
$six = $s->fetch();
$ss = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='07';");
$sept = $ss->fetch();
$h = $cnx->query("SELECT ABS(sum(montant))  FROM impaye WHERE code_impaye='08';");
$huit = $h->fetch();
?>
<html>

<head>
    <link rel="stylesheet" href="../page.css">
    <meta charset="utf-8">
    <title>Somme des impayés</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./node_modules/jspdf/dist/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src=" https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js "></script>
    <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>
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
                <a href="impayespo.php" class="option">Liste</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="graphimpayespo.php" class="option active">Somme des impayés</a>

                <!--GRAPHE IMPAYES PAR COMPTE CLIENT -->
                <a href="evolutionimpaye.php" class="option">Évolution des impayés</a>

            </div>
        </section>
        <button id="exportPdf" style='margin:2.5vh 0'>Télécharger le graphique</button>
        <div style="width:37%;">
            <canvas id="myChart">
                <script>
                    const backgroundColorPlugin = {
                        id: 'customBackgroundColor',
                        beforeDraw: (chart) => {
                            const ctx = chart.canvas.getContext('2d');
                            ctx.save();
                            ctx.fillStyle = '#272528';
                            ctx.fillRect(0, 0, chart.width, chart.height);
                            ctx.restore();
                        }
                    };
                    Chart.register(backgroundColorPlugin);
                    var xValues = ["Fraude à la carte", "Compte à découvert", "Compte clôturé", "Compte bloqué",
                        "Provision insuffisante", "Opération contestée par le débiteur", "Titulaire décédé",
                        "Raison non communiqué, contactez la banque du client"
                    ];
                    <?php echo "var yValues = [$un[0],$deux[0],$trois[0],$quatre[0],$cinq[0],$six[0],$sept[0],$huit[0]];"; ?>
                    var barColors = [
                        "#9E00FF",
                        "#7823AC",
                        "#9357B8",
                        "#593A6D",
                        "#C264FC",
                        "#8B12D6",
                        "#9E00FF",
                        "#995AC1"
                    ];

                    var myChart = new Chart("myChart", {
                        type: "doughnut",
                        data: {
                            labels: xValues,
                            datasets: [{
                                backgroundColor: barColors,
                                data: yValues
                            }]
                        },
                        options: {
                            title: {
                                display: false,
                                text: "Somme des impayés (en euros)"
                            }
                        }
                    });
                    document.getElementById('exportPdf').addEventListener('click', async function() {
                        const {
                            jsPDF
                        } = window.jspdf; // Import jsPDF
                        const pdf = new jsPDF({
                            orientation: "landscape"
                        }); // Create a new PDF instance

                        // Convert chart to Base64 image
                        const chartImage = myChart.toBase64Image();

                        // Add the image to the PDF
                        pdf.addImage(chartImage, 'PNG', 10, 10, 190,
                            190); // (image, format, x, y, width, height)

                        // Save the PDF
                        pdf.save('somme_impaye.pdf');
                    });
                </script>
            </canvas>
        </div>

        <!-- <script src="exports.js"></script> -->


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