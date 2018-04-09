<?php
require_once __DIR__ .'/../include/init.php';
adminSecurity();
	
	$errors = [];
	$nom = '';
if(!empty($_POST)){// si on a des données venant du formulaire 
	// nettoyage des données venues du formulaire 
	sanitizePost();
	// cree des variables a partir d'un tableau (les variables ont les noms des cles dans le tableau)
	extract($_POST);
	if(empty($_POST['nom'])){
		$errors[]= 'le nom est obligatoire';
	}
	elseif(strlen($_POST['nom'])> 50){
		$errors[] = 'le nom ne doit pas faire plus de 50 caracteres';

	}

	// si le formulaire est correctement rempli
	if (empty($errors)){
		if(isset($_GET['id'])){
			$query = 'UPDATE categorie SET nom = :nom WHERE id = :id';
			$stmt = $pdo->prepare($query);
			$stmt->bindValue(':nom', $_POST['nom']);
			$stmt->bindValue(':id', $_GET['id']);
			$stmt->execute();



		}else{
			$query = ' INSERT INTO categorie(nom) VALUES (:nom)';
			$stmt = $pdo->prepare($query);
			$stmt->bindValue(':nom', $_POST['nom']);
			$stmt->execute();
		}
// enregistrement d'un message en session
		setFlashMessage('la categorie est enregistrée');
//redirection vers la pages categories.php
		header('location: categories.php');
		die;
	}

}
	elseif(isset($_GET['id'])){
		// en modification, si on a pas de retour de formulaire 
		// on va aller chercher la categorie en bdd pour l'affichage 
		$query = 'SELECT * FROM categorie WHERE id='.$_GET['id'];
		$stmt = $pdo->query($query);
		$categorie = $stmt->fetch();
		$nom = $categorie['nom'];
	}
include __DIR__. '/../layout/top.php';
?>
<h1>Edition categorie</h1>
<?php
	if(!empty($errors)):
?>
	<div class="alert alert-danger">
		<h5 class="alert-heading">Le formulaire contient des erreurs</h5>
		<?= implode('<br>', $errors);//implode transform un tableau en chaine de caractere?>
	</div>
<?php
endif;
?>



<form method="post">
	<div class="form-group">
		<label>nom</label>
		<input type="text" name="nom" class="form-control" value = "<?= $nom; ?>">
	</div>
	<div class="form-btn-group text-right">
		<button type="submit" class="btn btn-primary">Enregistrer</button>
		<a href="categories.php" class="btn btn-secondary">retour</a>
	</div>
</form>
<?php
include __DIR__. '/../layout/bottom.php';
