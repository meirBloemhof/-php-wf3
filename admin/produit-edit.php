<?php
// faire le formulaire d'edition de produit 
// - nom : input text
// -description : text area
// -reference : input text
// -prix : input text
// -categorie select 

// adapter la page  pour la modification 
// avoir un bouton dans la page de liste qui pointe vers cette page en passant d'id du produit dans l'url 
// si on a un produit dans l'url sans retour de post , faire une requete select pour pre remplir le formulaire 
// adapter le traitement du formulaire pour faaire un update au lieu d'un insert si on a l'id dans l'url 
// adapter la verification  de l'unicite de la reference pour exclure la reference du produit que l'on modifie de la requete 
require_once __DIR__ .'/../include/init.php';
adminSecurity();
	$query= 'SELECT * FROM categorie';
	$stmt = $pdo->query($query);
	$categories = $stmt->fetchAll();
	$errors = [];
	$nom = $prix = $description = $reference = $categorieId = $photoActuelle = '';
if(!empty($_POST)){// si on a des données venant du formulaire 
	// nettoyage des données venues du formulaire 
	sanitizePost();
	// cree des variables a partir d'un tableau (les variables ont les noms des cles dans le tableau)
	extract($_POST);
	$categorieId = $_POST['categorie'];
	$photoActuelle = $produit['photo'];
	if(empty($_POST['nom'])){
		$errors[]= 'le nom est obligatoire';
	}
	elseif(strlen($_POST['nom'])> 50){
		$errors[] = 'le nom ne doit pas faire plus de 50 caracteres';

	}
	if(empty($_POST['description'])){
		$errors[]= 'la description est obligatoire';
	}
	if(empty($_POST['reference'])){
		$errors[]= 'la reference est obligatoire';
	}elseif(strlen($_POST['reference'])>50){
		$errors[] = 'la reference ne doit pas depasser 50 caracteres';
	}else {
		$query = 'SELECT COUNT(*) FROM produit WHERE reference = :reference';
		// en modification on exclut de la verification le produit que l'on est en train de modifier 
		if(isset($_GET['id'])){
			$query .= 'AND id !=' .$_GET['id'];
		}
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':reference', $_POST['reference']);
		$stmt->execute();
		$nb = $stmt->fetchColumn();

		if($nb != 0){
			$errors[]= "il existe deja une reference avec ce nom";
		}
	}
	if(empty($_POST['prix'])){
		$errors[]= 'le prix est obligatoire';
	}
	if(empty($categorieId)){
		$errors[]= 'vous devez selectionner une categorie';
	}
	if(!empty($_FILES['photo']['tmp_name'])){
		if($_FILES['photo']['size']>1000000){
			$errors[]= 'La photo ne doit pas faire plus de 1Mo';
		}
	$allowedMimeTypes = [
		'image/png',
		'image/jpeg',
		'image/gif'
	];
		if(!in_array($_FILES['photo']['type'], $allowedMimeTypes)){
			$errors[]= 'La photo  doit etre une image de type GIF, JPG, PNG';

		}
	}

	if(empty($errors)){
		if(!empty($_FILES['photo']['tmp_name'])){
			$originalName = $_FILES['photo']['name'];
			// on retrouve l'extension du fichier original a partir de son nom 
			// (ex.png pour mon_fichier.png )
			$extension = substr($originalName, strrpos($originalName, '.'));
			// le nom que va avoir le fichier dans le repertoir photo 
			$nomPhoto = $_POST['reference'] .$extension;
// en modification si le produit avait deja une photo
			if(!empty($photoActuelle)){
				// alors on on l'a supprime
				unlink(PHOTO_DIR. $photoActuelle);
			}
			// enregistrement du fichier dans le repertoire photo 
			move_uploaded_file($_FILES['photo']['tmp_name'], PHOTO_DIR.$nomPhoto);

		}else{
			$nomPhoto = $photoActuelle;
		}


		if(isset($_GET['id'])){
		$query = 'UPDATE produit SET nom = :nom, description = :description, reference = :reference, prix = :prix, categorie_id = :categorie_id,  photo = :photo WHERE id = :id';
		$stmt = $pdo->prepare($query);

		$stmt->bindValue(':nom', $_POST['nom']);
		$stmt->bindValue(':description', $_POST['description']);
		$stmt->bindValue(':reference', $_POST['reference']);
		$stmt->bindValue(':prix', $_POST['prix']);
		$stmt->bindValue(':categorie_id', $_POST['categorie']);
		$stmt->bindValue(':photo', $nomPhoto);
		$stmt->bindValue(':id', $_GET['id']);
		$stmt->execute();
		setFlashMessage('Votre produit est créé');
		header('Location: produits.php');
		die;

		}
		else{	
			$query = <<<EOS
		INSERT INTO produit(
			nom,
			description,
			reference,
			prix,
			categorie_id,
			photo
			)VALUES(
			:nom,
			:description,
			:reference,
			:prix,
			:categorie_id,
			:photo
		)
EOS;
			$stmt = $pdo->prepare($query);
			$stmt->bindValue(':nom', $_POST['nom']);
			$stmt->bindValue(':description', $_POST['description']);
			$stmt->bindValue(':reference', $_POST['reference']);
			$stmt->bindValue(':prix', $_POST['prix']);
			$stmt->bindValue(':categorie_id', $_POST['categorie']);
			$stmt->bindValue(':photo', $nomPhoto);
			$stmt->execute();

			setFlashMessage('Votre produit est créé');
			header('Location: produits.php');
			die;

		
		}
	}
}


	
elseif(isset($_GET['id'])){
	// en modification, si on a pas de retour de formulaire 
	// on va aller chercher la categorie en bdd pour l'affichage 
	$query = 'SELECT * FROM produit WHERE id='.$_GET['id'];
	$stmt = $pdo->query($query);
	$produit = $stmt->fetch();
	$nom = $produit['nom'];
	$description = $produit['description'];
	$reference = $produit['reference'];
	$prix = $produit['prix'];
	$categorieId = $produit['categorie_id'];
	$photoActuelle = $produit['photo'];
}
include __DIR__. '/../layout/top.php';
?>
<h1>Edition produit</h1>
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

<!--  l'attribut enctype est obligatoire pour un formulaire qui contient un telechargement dede fichier   -->
<form method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label>nom</label>
		<input type="text" name="nom" class="form-control" value = "<?= $nom; ?>">
	</div>
	<div class="form-group">
		<label>description</label>
		<textarea name="description"  class="form-control"><?= $description; ?></textarea>
	</div>
	<div class="form-group">
		<label>reference</label>
		<input type="text" name="reference" class="form-control" value = "<?= $reference; ?>">
	</div>
	<div class="form-group">
		<label>prix</label>
		<input type="text" name="prix" class="form-control" value = "<?= $prix; ?>">
	</div>
	<div class="form-group">
		<label>categorie</label>
		<select name="categorie" class="form-control">
			<option value=" "></option>
		<?php
			foreach ($categories as $categorie) {
				$selected= ($categorie['id']==$categorieId)
			   ? 'selected'
			   : ''
			   ;

		?>
			<option value="<?=$categorie['id']?>"<?= $selected?>><?=$categorie['nom']?></option>
		<?php
		}
		?>

		</select>
	</div>
		<div class="form-group">
			<label>photo</label>
			<input type="file" name="photo">
		</div>
		<?php
			if (!empty($photoActuelle)):
				echo '<p>Actuellement: <br><img src="'.PHOTO_WEB. $photoActuelle.'" height="150px"></p>';
			endif;
		?>
	<input type="hidden"  name="photoActuelle" value="<?$photoActuelle; ?>">
	<div class="form-btn-group text-right">
		<button type="submit" class="btn btn-primary">Enregistrer</button>
		<a href="produits.php" class="btn btn-secondary">retour</a>
	</div>
</form>