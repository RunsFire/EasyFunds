<?php
session_start();
include('connexion.inc.php');
if ($_SESSION['typeu'] != 1 ||  !isset($_SESSION['login']) && !isset($_SESSION['mdp'])) {
    header('location:login.php');
}

$num = $_GET['num'];
$result = $cnx->query("SELECT num, mail FROM utilisateur u Join demande_compte d ON u.num=d.num_utilisateur WHERE num_demande=$num ;");
if ($result->rowCount() > 0) {
    $result2 = $result->fetch(PDO::FETCH_ASSOC);
    $num_utilisateur = $result2['num'];
    $mail = $result2['mail'];
} else {
    $_SESSION['debloquer'] = "echouer";
    header('Location:admin_demande.php');
}

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
if (debloqueUtil($num_utilisateur)) {
    // on envoie un mail a l'utilisateur
    $_SESSION['debloquer'] = "effectuer";
    $_SESSION['debloque_utilisateur'] = $mail;
    include("mail/maildebloqueutilisateur.php");
    unset($_SESSION['debloque_utilisateur']);
    // on supprimer la demande
    suppDemande($num);
} else {
    $_SESSION['debloquer'] = "echouer";
}
// on redirige vers la page de demande
header('Location:admin_demande.php');