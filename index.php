<?php
require_once 'functions.php';
startSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage - Toy Shop</title>
</head>
<body>
<h1>Toy Website Homepage</h1>
<?php
echo createNav('Navigation Menu', 'login.php', array(
    'index.php' => 'Homepage',
    'orderToysForm.php' => 'Order Toys',
    'selectToy.php' => 'Select a Toy to Edit',
    'credits.php' => 'Credits'));
?>
<aside id="offers">
    <h3>Special Offers!</h3>
    <h4 id="toyName">Toy Name</h4>
    <p id="catDesc">Cat Desc</p>
    <p id="toyPrice">Toy Price</p>
</aside>
<script type='text/javascript' src='displayOffers.js'></script>
</body>
</html>