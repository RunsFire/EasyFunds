<?php
session_start();
include('connexion.inc.php');
if ($_SESSION['typeu'] != 1 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:login.php');
}

$num = $_GET['num'];
$result = $cnx->query("SELECT mail,type_demande FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE num_demande=$num ;");
$result2 = $result->fetch(PDO::FETCH_ASSOC);
$mail = $result2['mail'];

function suppDemande($num)
{
    //supprimer la demande
    global $cnx;
    $result = $cnx->exec("DELETE FROM demande_compte WHERE num_demande = $num");
    return $result;
}

function suppUsr($num)
{
    global $cnx;
    $result = $cnx->exec("DELETE FROM utilisateur WHERE num= '$num'");
    return $result;
}

if (suppUsr($num)) {
    $_SESSION['supp_utilisateur'] = "effectuer";
    $_SESSION['supp_utilisateur_mail'] = $mail;
    include("mail/mailsupputilisateur.php");
    unset($_SESSION['supp_utilisateur_mail']);
    suppDemande($num);
} else {
    $_SESSION['supp_utilisateur'] = "echouer";
}