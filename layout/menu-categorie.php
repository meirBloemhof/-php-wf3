<?php


$query='SELECT *FROM categorie';
$stmt= $pdo->query($query);
$categoriesMenu = $stmt->fetchAll();

?>


<div class="navbar-collapse">
	<ul class="navbar-nav">
		
<?php 
foreach($categoriesMenu as $categorieMenu){
?>

		<li class="nav-item">
			<a href="<?=RACINE_WEB; ?>categorie.php?id=<?=$categorieMenu['id']; ?> " class="nav-link">
				
			<?= $categorieMenu['nom']; ?>
			</a>
		</li>

<?php
}
?>

	</ul>
</div>