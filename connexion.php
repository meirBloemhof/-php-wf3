<?php
require_once __DIR__ .'/include/init.php';
$email= '';

if(!empty($_POST)){
	sanitizePost();
	extract($_POST);
	if(empty($_POST['email'])){
		$errors[]= 'le mail est obligatoire';
	}
	if(empty($_POST['mdp'])){
		$errors[]= 'le mot de passe est obligatoire';
	}
	if(empty($errors)){
		$query= 'SELECT * FROM utilisateur WHERE email = :email';
		$stmt = $pdo-> prepare($query);
		$stmt->bindValue(':email', $_POST['email']);
		$stmt->execute();

		$utilisateur = $stmt->fetch();

		// si on a un utilisateurs en bdd avec l'email saisi 
		if(!empty($utilisateur)){
			// si le mdp saisi correspond au mdp encrypte en bdd 
			if(password_verify($_POST['mdp'], $utilisateur['mdp'])){
				$_SESSION['utilisateur'] = $utilisateur;
				header ('location: index.php');
				die;
			}
		}
		$errors[]= 'identification ou mot de passe incorrect ';
	}
}

include __DIR__. '/layout/top.php';
if(!empty($errors)):
?>
	<div class="alert alert-danger">
		<h5 class="alert-heading">Le formulaire contient des erreurs</h5>
		<?= implode('<br>', $errors ); ?>
	</div>

<?php

endif;
?>
<h1>page de connexion</h1>
<p>merci de vous identifier</p>
<form method="post">
	<div class="form-group">
		<label>Email</label>
		<input type="text" name="email" value="<?= $email; ?>" class="form-control">
	</div>
			<div class="form-group">
		<label>Mot de passe</label>
		<input type="password" name="mdp"  class="form-control">
	</div>
		<div class="form-btn-group text-right">
		<button type="submit" class="btn btn-primary">Se connecter</button>
	</div>





</form>
<?php
include __DIR__. '/layout/bottom.php';
?>