<?php
// si le panier est vide afficher un message 
// sinon afficher un tableau HTML  avec pour chaque  produit du panier :
// le nom du produit, son prix unitaire , la quantite , le prix total pour le produit 
// faire une fonction getTotalPanier() qui calculera le montant total du panier et l'utiliser sous le tableau pour afficher le total 


require_once __DIR__ .'/include/init.php';
if(isset($_POST['commander'])){
/*
enregistrer la commande et son detail en bdd 
afficher un message de confirmation 
vider le panier 

*/
$query = <<<EOS
INSERT INTO commande(
			montant_total,
			utilisateur_id
			)VALUES(
			:montant_total,
			:utilisateur_id
		)
EOS;
			$stmt = $pdo->prepare($query);
			$stmt->bindValue(':montant_total', getTotalPanier());
			$stmt->bindValue(':utilisateur_id', $_SESSION['utilisateur']['id']);
			$stmt->execute();
			$commande_id = $pdo->lastInsertId();

$query = <<<EOS
INSERT INTO detail_commande(
		commande_id,
		produit_id,
		prix,
		quantite
)VALUES(
		:commande_id,
		:produit_id,
		:prix,
		:quantite		
)			
EOS;
		$stmt = $pdo->prepare($query);
		$stmt->bindValue(':commande_id', $commande_id);

		foreach ($_SESSION['panier'] as $id => $panier) {
		
			$stmt->bindValue(':produit_id', $id);
			$stmt->bindValue(':prix', $panier['prix']);
			$stmt->bindValue(':quantite', $panier['quantite']);
			$stmt->execute();

		}
		setFlashMessage('votre commande est validée');
		$_SESSION['panier'] = [];
}


// echo '<pre>';
// var_dump($_SESSION['panier']);
// echo '</pre>';
if(isset($_POST['modifier-quantite'])){
	modifierQuantitePanier($_POST['id'], $_POST['quantite']);
	setFlashMessage('la quantite a ete modifie');
}
if(empty($_SESSION['panier'])){
	setFlashMessage('votre panier est vide allez depenser votre argent en cliquant sur categorie');
}
include __DIR__. '/layout/top.php';
?>
<h2>Panier</h2>
<?php
if(!empty($_SESSION['panier'])){
?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th class="text-center" scope="col">Nom du produit</th>
      <th class="text-center" scope="col">Prix unitaire</th>
      <th class="text-center" scope="col">Quantité</th>
      <th class="text-center" scope="col">Prix total</th>
    </tr>
  </thead>
  <tbody>

  	<?php

  		foreach ($_SESSION['panier'] as $id => $panier) {
  	?>
    <tr>
      <td class="text-center"><?=$panier['nom']; ?></td>
      <td class="text-center"><?=prixFr($panier['prix']); ?></td>
      <td >
      	<form method="post" class="form-inline">
      		<input type="number" name="quantite" value="<?= $panier['quantite']; ?>" class="form-control col-sm-2" min="0">
      		<input type="hidden" name="id" value="<?= $id; ?>">
      		<button type="submit" name="modifier-quantite" class="btn btn-primary">modifier</button>
      	</form>
      	</td>
      <td class="text-center"><?=$panier['prix']*$panier['quantite']; ?></td>
    	</tr>
    <?php
}
?>


<?php
} 

if(!empty($_SESSION['panier'])){

?>
<tr>
    <th colspan="3">Total</th>
   <td class="text-center"><?=prixFr(getTotalPanier()); ?></td>
</tr> 
  </tbody>
</table>

<form method="post">
		<p class="text-right">
			<button type="submit" name="commander" class="btn btn-primary"> Valider la commande. </button>
		</p>
	</form>
<?php
}else{
?>
	  </tbody>
</table>
<?php
}
if (!isUserConnected()){
?>
	<div class="alert alert-info">
		Vous devez vous connecter ou vous inscrire pour valider la commande!
	</div>
<?php 
}
?>

