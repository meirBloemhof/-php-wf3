<!-- 
lister les commandes dans un tableau HTML :
- id de la commande
- nom prénom de l'utilisateur qui a passé la commande
- montant formaté
- date de la commande formaté (function date() et strtotime() de PHP)
-  statut 
- date de la statut commande formaté (function date() et strtotime() de PHP) -->
<?php

require_once __DIR__ . '/../include/init.php';
adminSecurity();
$options = array('en cours', 'envoye', 'livre');


if(isset($_POST['modifier-statut'])){
$query = 'UPDATE commande SET statut = :statut, date_statut= now() WHERE id = :id';
$stmt = $pdo->prepare($query);
$stmt->bindValue(':statut', $_POST['statut']);
$stmt->bindValue(':id', $_POST['id']);
$stmt->execute();
setFlashMessage('le statut est modifie');
}

$query =<<<EOS
   SELECT concat_ws(' ',utilisateur.nom, utilisateur.prenom )AS username, commande.montant_total, commande.date_commande , commande.statut, commande.id, commande.date_statut
   FROM commande
   JOIN utilisateur  ON utilisateur.id = commande.utilisateur_id
EOS;
$stmt = $pdo->query($query);

$commandes = $stmt->fetchAll();

include __DIR__ . '/../layout/top.php';
?>

<h2>Gestion commandes</h2>
<table class="table table-bordered">
  <thead>
    <tr>
      <th class="text-center" scope="col">reference commande </th>
      <th class="text-center" scope="col">nom/prenom</th>
      <th class="text-center" scope="col">montant</th>
      <th class="text-center" scope="col">date commande</th>
      <th class="text-center" scope="col">statut</th>
      <th class="text-center" scope="col">date statut</th>
    </tr>
  </thead>
  <tbody>

  	<?php

  		foreach ($commandes as $commande) {

  	?>
    <tr>
      <td class="text-center"><?=$commande['id']; ?></td>
      <td class="text-center"><?=$commande['username']; ?></td>
      <td class="text-center"><?=prixFr($commande['montant_total']); ?></td>
      <td class="text-center"><?=dateFr($commande['date_commande']); ?></td>
      <td class="text-center"><form method="post" class="form-inline"><select name="statut" class="form-control">
<?php
	foreach($options as $option){
			$selected = ($option ==  $commande['statut'])
			? 'selected'
			: ''
		;
?>
     
	<option value="<?=$option; ?>"<?=$selected;?>><?=ucfirst($option);?>
	</option>


     

<?php
}
?>
		 </select>
		 <input type="hidden" name="id" value="<?=$commande['id']?>">
		<button type="submit" class="btn btn-primary" name="modifier-statut"> Modifier</button>
		</form>
   		</td>
      <td class="text-center"><?=dateFr($commande['date_statut']); ?></td>
    	</tr>
 <?php
}

?>

  </tbody>
</table>


<?php
include __DIR__ . '/../layout/bottom.php';
?>


