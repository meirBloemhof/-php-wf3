<?php
require_once __DIR__ .'/../include/init.php';
adminSecurity();
//lister les categories dans un tableau HTML
$query = 'SELECT * FROM categorie ';
$stmt = $pdo->query($query);

$categories = $stmt->fetchAll();
// le requetage ici 
include __DIR__. '/../layout/top.php';
?>
<h1>Gestion categories</h1>
<p><a class="btn btn-primary" href="categorie-edit.php">Ajouter une categorie</a></p>
<?php
     echo '<table class="table table-striped">';
     echo '<tr>';
     echo '<th>Id</th>';
     echo '<th>Nom</th>';
     echo '<th width="250px"></th>';
     echo '</tr>';
     foreach($categories as $categorie):
 ?>
		<tr>
			<td><?=$categorie['id']; ?></td>
			<td><?=$categorie['nom']; ?></td>
			<td><a class="btn btn-info"
				href="categorie-edit.php?id=<?=$categorie['id']; ?>">modifier</a>
				<a class="btn btn-danger"
				href="categorie-delete.php?id=<?=$categorie['id']; ?>">supprimer</a>
			
			</td>
		</tr>
<?php
endforeach;

echo '</table>';
?>
<?php
include __DIR__. '/../layout/bottom.php';


