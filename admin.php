<!DOCTYPE html>
<?php session_start (); error_reporting(E_ALL); ini_set("display_errors", 1); if (!isset($_SESSION['raison_social'])){
    $_SESSION['raison_social']="%" ; $_SESSION['mail']="%" ; } 
    // if($_SESSION['typeu']!=1 || !isset($_SESSION['login'])&& !isset($_SESSION['mdp'])) { 
    // header('location:login.php'); 
    // } 
?>
<html>

<head>
    <link rel="stylesheet" href="page.css">
    <meta charset="utf-8">
    <title>Accueil</title>
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
        <a class="tab active" href="">Client</a>
        <a class="tab" href="">Crée un compte</a>
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
        </section>


        <!-- DISPLAY TABLES + DATAS -->
        <section class="table-display">

            <!-- PO TOUS CLIENTS -->
            <div id="liste-utilisateur" class="display active">

                <!-- FILTRES -->
                <div class="frame filtres">
                    <form method="GET" action="admin_des_compte_client.php">
                        <?php
                            if (!empty($_GET['raison_social'])){
                                echo '<input type="text" name="raison_social" class="filtre" value='.$_GET['raison_social'].' placeholder='.$_GET['raison_social'].'>';
                            }else{
                                echo '<input type="text" name="raison_social" class="filtre" placeholder="Raison social">';
                            }if (!empty($_GET['mail'])){
                                echo '<input type="text" name="mail" class="filtre" value='.$_GET['mail'].' placeholder='.$_GET['mail'].'>';
                            }else{
                                echo '<input type="text" name="mail" class="filtre" placeholder="Mail">';
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
                                <th style="width:40%">Raison_social</th>
                                <th style="width:40%">Mail</th>
                                <th style="width:20%">Actions</th>
                            </tr>
                        </table>
                    </div>
                    <!-- TABLEAU DATAS -->
                    <div class="table-datas">
                        <table class="frame">
                            <?php
                                include("connexion.inc.php");
                                $var = 0;
                                if (!empty($_GET['raison_social'])){
                                    $_SESSION['raison_social'] =$_GET['raison_social']."%";
                                } if (!empty($_GET['mail']) ){
                                    $_SESSION['mail'] = "%".$_GET['mail']."%";
                                } if (!empty($_GET['reset'])){
                                    $_SESSION['raison_social']= "%";
                                    $_SESSION['mail']= "%";   
                                    unset($_GET['reset']);
                                }
                                $utilisateurs = $cnx-> query("SELECT num,raison_social,mail FROM utilisateur WHERE typeU=0 AND raison_social LIKE \"".$_SESSION['raison_social']."\" AND mail LIKE\"".$_SESSION['mail']."\";");
                                if ($utilisateurs==null){
                                    echo "Pas d'utilisateurs";
                                }else {
                                    while( $ligne = $utilisateurs->fetch(PDO::FETCH_OBJ)){ 
                                        if ($var%2==0){
                                            echo "<tr class=\"style1\">";
                                        }else{
                                            echo "<tr class=\"style2\">";
                                        }?>
                            <td style="width:40%"><?= $ligne->raison_social ?>N</td>
                            <td style="width:40%"><?= $ligne->mail?></td>
                            <td style="width:20%">

                                <bouton onclick="supprimer_utilisateur('<?= $ligne->num ?>')">Supprimer</bouton>

                            </td>
                            </tr>
                            <?php
                                       $var++;
                                }
                                }
                                $utilisateurs->closeCursor();
                            ?>
                        </table>
                    </div>
                </div>
        </section>
        <script>
        function supprimer_utilisateur(num) {
            const result = confirm('Voulez vous supprimer cet utilisateur');
            if (result) {
                document.location.replace('supp_utilisateur.php?num=' + num);
            }
        }
        </script>

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
