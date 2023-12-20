<?php
require_once 'functions.php';
startSession();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Selected Toy</title>
</head>
<body>
<h1>Edit toy Form</h1>
<?php
echo createNav('Navigation Menu', 'login.php', array(
    'index.php' => 'Homepage',
    'orderToysForm.php' => 'Order Toys',
    'selectToy.php' => 'Select a Toy to Edit',
    'credits.php' => 'Credits'
));
if (!checkLoginStatus()) {
    notLoggedIn(); // if not logged in display error message
} else {
try {
    $dbConn = getConnection(); // connecting to the database


    $toyID = filter_has_var(INPUT_GET, 'toyID') ? $_GET['toyID'] : null;
    $toyID = filter_var($toyID, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
    if (empty($toyID)) {
        echo "<p>Toy ID is incorrect ensure it is valid</p>";
    } else {

        // Query to be used to fetch the toy information
        $toyQueryObject = prepareAndExecuteQuery('SELECT toyName, description, NTL_toys.catID, catDesc, NTL_toys.manID, manName, toyPrice FROM NTL_toys
                    INNER JOIN NTL_category ON NTL_toys.catID = NTL_category.catID
                    INNER JOIN NTL_manufacturer ON NTL_toys.manID = NTL_manufacturer.manID
                    WHERE toyID =:toyID', array(':toyID' => $toyID)); // Calls a function which prepares and executes an SQL query then returns the row object
        $toyRowObj = $toyQueryObject->fetchObject(); // fetch the next row object, will only be one since we are filtering by primary key

        if (!isset($toyRowObj->toyName)) { // If the toyID is edited in the url to one that does not exist this will check to see if the SQL query returned values if not it will display an error message
            echo "<p>Toy ID was incorrect! Ensure you have not edited the toyID</p>";
        } else { // display rest of the form


            // Fetching the cat IDs + Desc and filtering out the one we already have to prevent duplicates in our select form.
            $categoriesQueryObj = prepareAndExecuteQuery('SELECT catID, catDesc FROM NTL_category WHERE NOT catID = :catID', array(':catID' => $toyRowObj->catID)); // Store the query object for use later


            // Fetching the manufacturer IDs + Desc and filtering out the one we already have to prevent duplicates in our select form.
            $manufacturesQueryObj = prepareAndExecuteQuery('SELECT manID, manName FROM NTL_manufacturer WHERE NOT manID = :manID', array(':manID' => $toyRowObj->manID)); // Store the query object for use later

// Creating the output form dynamically fetching the catIDs, catDesc, manIDs, manDesc and displaying the clicked on toys information. All data displayed has been sanitized to prevent cross site scripting
            echo '
<form id="editToy" action="updateToy.php" method="post">
    <input type="hidden" name="toyID"  value=' . $toyID . '>
    <br>
    <label>Toy Name</label>
    <br>
    <textarea name="toyName">' . filter_var($toyRowObj->toyName, FILTER_SANITIZE_SPECIAL_CHARS) . '</textarea>
    <br>    
    <label>Toy Category</label>
    <br>
    <select name="toyCategories">
    <option selected value=' . filter_var($toyRowObj->catID, FILTER_SANITIZE_SPECIAL_CHARS) . '>' . filter_var($toyRowObj->catDesc, FILTER_SANITIZE_SPECIAL_CHARS) . '</option>';
            while ($categoriesRowObj = $categoriesQueryObj->fetchObject()) {
                echo '<option value=' . filter_var($categoriesRowObj->catID, FILTER_SANITIZE_SPECIAL_CHARS) . '>' . filter_var($categoriesRowObj->catDesc, FILTER_SANITIZE_SPECIAL_CHARS) . '</option>';
            }
            echo '</select>
    <br>    
    <label>Toy Manufacturer</label>
    <br>
    <select name="toyManufacturer">
    <option selected value=' . filter_var($toyRowObj->manID, FILTER_SANITIZE_SPECIAL_CHARS) . '>' . filter_var($toyRowObj->manName, FILTER_SANITIZE_SPECIAL_CHARS) . '</option>';
            while ($manufacturesRowObj = $manufacturesQueryObj->fetchObject()) {
                echo '<option value=' . filter_var($manufacturesRowObj->manID, FILTER_SANITIZE_SPECIAL_CHARS) . '>' . filter_var($manufacturesRowObj->manName, FILTER_SANITIZE_SPECIAL_CHARS) . '</option>';
            }
            echo '</select>
    <br>    
    <label>Toy Description</label>
    <br>    
    <textarea name="toyDescription">' . filter_var($toyRowObj->description, FILTER_SANITIZE_SPECIAL_CHARS) . '</textarea>
    <br>    
    <label>Toy Price</label>
    <br>    
    <input name="toyPrice" type="number" value=' . filter_var($toyRowObj->toyPrice, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) . '>
    <br>
    <input type="submit" value="Update Toy">    
</form>';
        }
    }
} catch (Exception $e) { // Will catch any error message created by the document
    exceptionHandler($e);
}
}

?>
</body>
</html>
