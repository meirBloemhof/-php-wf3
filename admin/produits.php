<?php
require_once __DIR__ .'/../include/init.php';
adminSecurity();
//lister les categories dans un tableau HTML
$query =<<<EOS
   SELECT p.*, c.nom AS categorie_nom
   FROM produit p
   JOIN categorie c ON p.categorie_id = c.id
EOS;
$stmt = $pdo->query($query);

$produits = $stmt->fetchAll();
// le requetage ici 
include __DIR__. '/../layout/top.php';
?>
<h1>Gestion produits</h1>
<p><a class="btn btn-primary" href="produit-edit.php">Ajouter un produit</a></p>


<?php
     echo '<table class="table table-striped">';
     echo '<tr>';
     echo '<th>Id</th>';
     echo '<th>Nom</th>';
     echo '<th>reference</th>';
     echo '<th>prix</th>';
     echo '<th>categorie</th>';
     echo '<th></th>';

     echo '</tr>';
     foreach($produits as $produit):
 ?>
		<tr>
			<td><?=$produit['id']; ?></td>
			<td><?=$produit['nom']; ?></td>
			<td><?=$produit['reference']; ?></td>
			<td><?=prixFr($produit['prix']); ?></td>
			<td><?=$produit['categorie_nom']; ?></td>
			<td><a class="btn btn-info"
				href="produit-edit.php?id=<?=$produit['id']; ?>">modifier</a>
				<a class="btn btn-danger"
				href="produit-delete.php?id=<?=$produit['id']; ?>">supprimer</a>
			
			</td>

			
		</tr>
<?php
endforeach;

echo '</table>';
?>
<?php
include __DIR__. '/../layout/bottom.php';


