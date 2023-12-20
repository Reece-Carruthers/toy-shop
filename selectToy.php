<?php
require_once 'functions.php';
startSession();
?>
<!DOCTYPE html >
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Select Toy Name to Edit </title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<h1> Select Toy to Edit </h1>
<?php
echo createNav('Navigation Menu', 'login.php', array(
        'index.php' => 'Homepage',
        'orderToysForm.php' => 'Order Toys',
        'selectToy.php' => 'Select a Toy to Edit',
        'credits.php' => 'Credits'
));

if (!checkLoginStatus()) {
    notLoggedIn(); // if not logged in display error message
    echo "</body></html>";
} else {
?>
<div class="toyContainer">
    <?php

    require_once("functions.php");

    try {
        $dbConn = getConnection(); // Connect to DB
        $sqlGetToyName = "SELECT toyID, toyName, description, catDesc, toyPrice  FROM NTL_toys 
                      INNER JOIN NTL_category ON NTL_toys.catID = NTL_category.catID
                      ORDER BY toyName ASC"; // Preparing SQL select statement

        $sqlResultGetToyName = $dbConn->query($sqlGetToyName); // Executing query

        while ($toyRowObj = $sqlResultGetToyName->fetchObject()) { // Iterating through results and displaying them to browser
            echo "
            <div class='toy'> 
                <span class='toyName'><a href='editToyForm.php?toyID={$toyRowObj->toyID}'>" . filter_var($toyRowObj->toyName, FILTER_SANITIZE_SPECIAL_CHARS) . "</a></span>
                <span class='toyDescription'>" . filter_var($toyRowObj->description, FILTER_SANITIZE_SPECIAL_CHARS) . "</span>
                <span class='toyCategory'>" . filter_var($toyRowObj->catDesc, FILTER_SANITIZE_SPECIAL_CHARS) . "</span>
                <span class='toyPrice'>£" . filter_var($toyRowObj->toyPrice, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) . "</span>
            </div>
             "; // Sanitizing the data displayed to the web browser to prevent cross site scripting
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }?>
    </div>
    </body>
    </html>
    <?php
    }
    ?>