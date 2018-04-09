<?php

function setFlashMessage($message, $type='success'){
	$_SESSION['flashMessage'] = [
		'message' => $message,
		'type' => $type
	];

}
function displayFlashMessage(){
	if (isset($_SESSION['flashMessage'])){
		$message = $_SESSION['flashMessage']['message'];
		$type = ($_SESSION['flashMessage']['type'] == 'error')
		? 'danger' // pour la classe alert-danger du bootstrap
		: $_SESSION['flashMessage']['type']
		;
		echo '<div class="alert alert-'.$type.'">'
		. '<h5 class= "alert-heading">' .$message .'</h5>'
		.'</div>'
		;
		unset($_SESSION['flashMessage']);
	}

}
function sanitizeValue(&$value){
	// trim() supprime les espaces en debuts et fin de chaine
	// strip_tags() supprime les balises HTML
	$value = trim(strip_tags($value));
}
function sanitizeArray(array &$array){
	// applique la fonction sanitizeValue sur tout les elements du tableau
	array_walk($array, 'sanitizeValue');
}
function sanitizePost(){
	sanitizeArray($_POST);
}
function isUserConnected(){
	return isset($_SESSION['utilisateur']);
}
function getUserFullName(){
	if (isUserConnected()) {
		return $_SESSION['utilisateur']['prenom']
		.' ' .$_SESSION['utilisateur']['nom'];
	}
}
function isUserAdmin(){
	return isUserConnected() && $_SESSION['utilisateur']['role'] == 'admin';
}
function adminSecurity(){
	if(!isUserAdmin()){
		if(!isUserConnected()){
			header('location:' .RACINE_WEB.'connexion.php');
		}else{
			header ('HTTP/1.1 403 Forbidden');
			echo "vous n'avez pas le droit d'acceder a cette page ";
		}
		die;
	}
}
function prixFr($prix){
	return number_format($prix, 2, ',', ' '). ' €';
}
function dateFr($dateSql){
	return date('d/m/Y   H:i:s', strtotime($dateSql));



}

function ajoutPanier(array $produit, $quantite){
	if(!isset($_SESSION['panier'])){
		$_SESSION['panier'] = [];
	}
	// si le produit n'est pas encore dans le panier
	if(!isset($_SESSION['panier'][$produit['id']])){
		$_SESSION['panier'][$produit['id']]= [
			'nom'=> $produit['nom'],
			'prix'=> $produit['prix'],
			'quantite'=> $quantite
		];
	}else{
	// 	si le produit existe deja dans le panier alors on meet a jour la quantite
		$_SESSION['panier'][$produit['id']]['quantite']+= $quantite;
	}
}
function getTotalPanier(){
	$prixTotal= 0;
	if(!empty($_SESSION['panier'])){
		foreach($_SESSION['panier'] as $panier){
			$prixTotal+=$panier['prix']*$panier['quantite'];
			}

		}
		return $prixTotal;
}
function modifierQuantitePanier($produitId, $quantite){
	if(isset($_SESSION['panier'][$produitId])){
		if($quantite !=0){
			$_SESSION['panier'][$produitId]['quantite']= $quantite;

		}else{
			unset($_SESSION['panier'][$produitId]);
		}
	}



}