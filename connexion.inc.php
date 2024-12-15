<?php
// Informations de connexion
$host = 'localhost';        // Serveur de base de données, souvent 'localhost' pour PlanetHoster
$dbname = 'zzytafus_easyfunds';  // Nom de ta base de données
$username = '[username]'; // Nom d'utilisateur de la base de données
$password = '[password]';  // Mot de passe de l'utilisateur

try {
    // Créer une nouvelle connexion PDO
    $cnx = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
} catch (PDOException $e) {
    // En cas d'échec de la connexion, afficher le message d'erreur
    echo "Erreur de connexion : " . $e->getMessage();
}
?>