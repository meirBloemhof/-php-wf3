<?php
require_once __DIR__ .'/../include/init.php';
adminSecurity();
$query= 'SELECT photo FROM produit WHERE id= '.$_GET['id'];
$stmt = $pdo->query($query);
$photo = $stmt->fetchColumn();
if(!empty($photo)){
// on supprime l'image du produit dans le repertoire photo s'il en a une 
	unlink(PHOTO_DIR . $photoActuelle);
}

$query = 'DELETE  FROM produit WHERE id='.$_GET['id'];

$pdo->exec($query);
setFlashMessage('le produit est supprimée');

header('location: produits.php');
die;