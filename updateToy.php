<?php
require_once 'functions.php';
startSession();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Update Toy</title>
</head>
<body>
<?php


echo createNav('Navigation Menu', 'login.php', array(
    'index.php' => 'Homepage',
    'orderToysForm.php' => 'Order Toys',
    'selectToy.php' => 'Select a Toy to Edit',
    'credits.php' => 'Credits'
));

if (!checkLoginStatus()) { // if not logged in display error message
    notLoggedIn();
} else {

    $formResults = validate_form(); // Stores the array results of validate form
    $errors = $formResults[1]; // Stores the $errors array into its own variable so it can be accessed easier


    if (!empty($errors)) { // if the error array has errors within displays them to the user
        show_errors($errors);
    } else { // begin PDO prepared statements

        try {

            $dbConn = getConnection(); // Connects to the database

            $inputs = $formResults[0]; // Gets the form inputs and stores them in an input array


            prepareAndExecuteQuery('UPDATE NTL_toys SET toyName = :toyName, description = :toyDescription,
                              manID = :manID, catID = :catID, toyPrice = :toyPrice WHERE toyID = :toyID', array(
                ':toyName' => $inputs['toyName'],
                ':toyDescription' => $inputs['toyDescription'],
                ':manID' => $inputs['toyManufacturer'],
                ':catID' => $inputs['toyCategories'],
                ':toyPrice' => $inputs['toyPrice'],
                ':toyID' => $inputs['toyID']));

            echo '<p>Toy has succesfully been updated!</p>';

        } catch (Exception $e) {
            exceptionHandler($e);
        }
    }?>
    </body>
    </html>
<?php
}
?>

