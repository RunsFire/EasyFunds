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
$type = $result2['type_demande'];
function suppDemande($num)
{
    //supprimer la demande
    global $cnx;
    $result = $cnx->exec("DELETE FROM demande_compte WHERE num_demande = $num");
    return $result;
}

// si la demande a bien été supprimer on envoie un mail a l'utilisateur
if (suppDemande($num)) {
    // on envoie un mail a l'utilisateur
    $_SESSION['supp_demande'] = "effectuer";
    if ($type == "b") {
        $_SESSION['type'] = "deblocage";
    } else if ($type == "s") {
        $_SESSION['type'] = "suppression";
    } else if ($type == "c") {
        $_SESSION['type'] = "création";
    }
    $_SESSION['demande_refuse_mail'] = $mail;
    include("mail/maildemanderefuser.php");
    // on supprimer les variables provisoires
    unset($_SESSION['type']);
    unset($_SESSION['demande_refuse_mail']);
} else {
    $_SESSION['supp_demande'] = "echouer";
}
// on redirige vers la page de demande
header('Location:admin_demande.php');