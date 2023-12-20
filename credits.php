<?php
require_once 'functions.php';
startSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Credits</title>
</head>
<body>
<h1>Credits</h1>
<H2>Reece Carruthers - W19011575</H2>
<?php
echo createNav('Navigation Menu', 'login.php', array(
'index.php' => 'Homepage',
'orderToysForm.php' => 'Order Toys',
'selectToy.php' => 'Select a Toy to Edit',
'credits.php' => 'Credits'));
?>
<h3>References</h3>
<h4>Used the below sites for general help with the concepts of PHP and Javascript and the different types of selectors within QuerySelector</h4>
<p>W3schools.com. 2021. HTML Tutorial. [online] Available at: &lt;https://www.w3schools.com/html/default.asp> [Accessed 20 December 2021].</p>
<p>W3schools.com. 2021. JavaScript Tutorial. [online] Available at: &lt;https://www.w3schools.com/js/default.asp> [Accessed 20 December 2021].</p>
<p>Php.net. 2021. PHP: Documentation. [online] Available at:  &lt;https://www.php.net/docs.php> [Accessed 20 December 2021].</p>
</body>
</html>