<?php
session_start();
include('connexion.inc.php');
if ($_SESSION['typeu'] != 1 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:login.php');
}

$num = $_GET['num'];

function suppUsr($num)
{
    global $cnx;
    $result = $cnx->exec("DELETE FROM demande_compte WHERE num_demande = $num");
    return $result;
}

if (suppUsr($num)) {
    $_SESSION['supp_demande'] = "effectuer";
} else {
    $_SESSION['supp_demande'] = "echouer";
}
header('Location:admin_demande.php');
