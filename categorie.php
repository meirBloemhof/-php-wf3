<?php
require_once __DIR__ .'/include/init.php';
adminSecurity();
$query= 'SELECT nom FROM categorie WHERE id= '.$_GET['id'];
$stmt= $pdo->query($query);
$titre= $stmt->fetch();


$query= 'SELECT * FROM produit WHERE categorie_id= '.$_GET['id'];
$stmt= $pdo->query($query);
$products= $stmt->fetchAll();

include __DIR__. '/layout/top.php';
?>
<h2><?= $titre['nom']?></h2>
<div class="card-group">
<?php
	foreach($products as $product){
	$src = (!empty($product['photo']))
	? PHOTO_WEB . $product['photo']
	: PHOTO_DEFAULT
;
?>
  <div class="card">
    <img style="width:150px" class="card-img-top" src="<?= $src;?>">
    <div class="card-body">
      <h5 class="card-title"><?= $product['nom'];?></h5>
      <p class="card-text"><small class="text-muted"><?=prixFr($product['prix']);?></small></p>
      <p class="card-text text-center">
       <a class="btn btn-primary" href="produit.php?id=<?=$product['id'];?>"> Voir la mere d'estelle en mini jupe</a>
     </p>

    </div>
  </div>
<?php
}
?>
</div>
<?php
include __DIR__. '/layout/bottom.php';
