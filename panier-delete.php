<?php
require_once __DIR__ .'/include/init.php';

unset($_SESSION['panier']);

header('location: index.php');