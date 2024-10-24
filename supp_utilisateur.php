<?php
session_start();
include('connexion.inc.php');
// if($_SESSION['typeu']!=1 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
// 	header('location:login.php');
// }

$num = $_GET['num'];

function suppUsr($num) {
    global $cnx;
    $result = $cnx->exec("DELETE FROM utilisateur WHERE num= '$num'");
    return $result;
}

if( suppUsr($num)){
    echo "
        <script>
            alert('Utilisateur supprimé');
            document.location.href = 'admin.php';
        </script>
    ";
} else{
    echo "
        <script>
            alert('Nous n'avons pas pu supprimé l'utilisateur!');
            document.location.href = 'admin.php';
        </script>
    ";
}
