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
        <a class="tab" href="">Créer un compte</a>
        <a class="tab active" href="admin_demande.php">Demandes</a>
    </div>
</header>

<body>
    <section class="container">






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