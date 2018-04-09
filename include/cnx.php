<?php
$pdo = new PDO(
	'mysql:host=localhost;dbname=boutique',//chaine de connexion
	'root',// nom d'utilisateur
	'root',	// mot de passe
	[
		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,//gestion des erreurs 
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8', 
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC 		// resultat en tableau associatif uniquement
	]
);