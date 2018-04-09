<!doctype html>
<html lang="fr">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>boutique</title>
  </head>
  <body>
    <?php 
      if(isUserAdmin()) :
    ?>
      <nav class="nabvar navbar-expand-md navbar-dark bg-dark">
      <div class="container navbar-nav">
        <a class="navbar-brand" href="#">admin</a>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>admin/categories.php">gestion categories</a>
          </li>
           <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>admin/produits.php">Gestion Produits
            </a>
          </li>
          <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>admin/commandes.php">Gestion commande
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <?php
  endif;
    ?>
    <nav class="navbar navbar-expand-md navbar-dark bg-secondary">
      <div class="container navbar-nav">
        <a class="navbar-brand" href="<?= RACINE_WEB; ?>index.php">boutique</a>
        <?php
          include __DIR__ .'/menu-categorie.php';
        ?>
        <ul class="navbar-nav">
          <?php
            if(isUserConnected()):
          ?>
            <li class="nav-item">
              <a class="nav-link">
                <?= getUserFullName(); ?>
              </a>
            </li>
                      <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>deconnexion.php">Deconnexion</a>
          </li>
              


          <?php
            else:
          ?>
          <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>inscription.php">inscription</a>
          </li>
                  <ul class="navbar-nav">
          <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>connexion.php">Connexion</a>
          </li>
          <?php
        endif;
        ?>
        <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>panier.php">Panier</a>
          </li>
                  <li class="nav-item">
            <a  class="nav-link" href="<?= RACINE_WEB; ?>panier-delete.php">vider le Panier</a>
          </li>
          
        </ul>
        </ul>
      </div>
    </nav>
   <div class="container">
    <?php
    displayFlashMessage();
    ?>