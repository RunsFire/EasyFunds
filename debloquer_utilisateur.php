<?php
session_start();
include('connexion.inc.php');
if ($_SESSION['typeu'] != 1 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:login.php');
}

$num = $_GET['num'];
$result = $cnx->query("SELECT mail FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE num_demande=$num ;");
$result2 = $result->fetch(PDO::FETCH_ASSOC);
$mail = $result2['mail'];
$type = $result2['type_demande'];

function suppDemande($num)
{
    //supprimer la demande
    global $cnx;
    $result = $cnx->exec("DELETE FROM demande_compte WHERE num_demande = $num");
    return $result;
}
function debloqueUtil($num)
{
    //debloquer l'utilisateur
    global $cnx;
    $result = $cnx->exec("UPDATE utilisateur set nbr_essai=0 WHERE num_demande = $num");
    return $result;
}

// si l'utilisateur a bien été debloquer on envoie un mail a l'utilisateur
if (debloqueUtil($num)) {
    $_SESSION['debloquer'] = "effectuer";
    $_SESSION['debloque_utilisateur'] = $mail;
    include("mail/maildebloqueutilisateur.php");
    unset($_SESSION['debloque_utilisateur']);
    suppDemande($num);
} else {
    $_SESSION['debloquer'] = "echouer";
}
header('Location:admin_demande.php');