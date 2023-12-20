<?php

function startSession()
{ // Sets the session path and if a session is not set, sets one
    ini_set('session.save_path', '/home/unn_w19011575/sessionData');
    if (!isset($_SESSION)) {
        session_start();
        if(!isset($_SESSION['logged-in'])){ // sets logged in to false if there is no logged-in session
            $_SESSION['logged-in'] = 'false';
        }
    }
}

function checkLoginStatus() // Checks if the user is logged in or not
{
    if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == 'true'){
        return true; // If logged in return true
    }
    else if (!isset($_SESSION['logged-in']) || isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == 'false') {
        return false; // If not logged in return false
    }

}

function getConnection() // Connects to a database
{
    try {
        $connection = new PDO('mysql:host=localhost;dbname=XXXXXXX!', 'XXXXXXX!', 'XXXXXXX!'); // Creates a PDO connection.
        $connection->setAttribute(PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION); // Sets the error mode
        return $connection; // Returns the connection information
    } catch (Exception $e) {
        throw new Exception('Connection Error ' . $e->getMessage(), 0, $e);
    }
}

function exceptionHandler($e)
{
    echo "<p><strong>An error has happened and has been logged, try again later!</strong></p>";
    log_errors($e);
}

function show_errors($errors)
{
    $errorSize = count($errors);
    echo '<p> There is a total of ' . $errorSize . ' errors with the data you entered</p>';
    for ($i = 0; $i < $errorSize; $i++) {
        echo '<p>Error ' . ($i + 1) . ': ' . $errors[$i] . '</p>';
    }
}

function log_errors($e)
{ // Stores any error message into a file

    if (!is_dir("logs")) { // if the logs directory does not exist create one
        mkdir("logs", 0777, true);
    }
    $file = fopen("logs/error_log.log", "ab"); // creates a log file or opens the log file if one exists
    $errorDate = date('D M j G:i:s T Y'); // Gets the date of the error
    $errorMsg = $e->getMessage(); // Gets the error message

    // Removes line breaks from the error message
    $toReplace = array("\r\n", "\n", "\r");
    $replaceWith = '';
    $errorMsg = str_replace($toReplace, $replaceWith, $errorMsg);

    fwrite($file, "$errorDate|$errorMsg" . PHP_EOL); // Writes the error date and msg into a file
    fclose($file); // Closes the file

}

function readLog($filelocation)
{
    if (file_exists($filelocation)) { // Checks to see if the log file exists if so
        $file = fopen($filelocation, 'rb'); // open in read mode
        while (!feof($file)) { // while not end of file
            $line = fgets($file); // get next line
            if ($line) { // if the line exists
                $line = trim($line); // trim the whitespace
                $part = explode('|', $line); // split it by the delimiter
                echo "<p>$part[0]: $part[1]</p>\n"; // output to browser
            }
        }
        fclose($file); // close the file
    } else {
        echo '<p>log file does not exists meaning there are no errors</p>'; // if the file does not exist means there is no log file so no errors
    }
}


// array(
//    "index.php" => "Home",
//    "books.php" => "Books",
//    "dvd.php" => "DVD",
//    "games.php" => "Games");

function createNav($navH2, $loginLink, array $links) // Takes in the name of the navigation, the link to the login page and the link to the logout script and array of links
{
    startSession();
    if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] == 'true') { // if user is not logged in text will say login
        $loginText = 'Logout';
    } else if(!isset($_SESSION['logged-in']) || $_SESSION['logged-in'] == 'false'){
        $loginText = 'Login';
    }
    $navContent = <<<NAVMENU
        <nav>
            <h2>$navH2 </h2>
            <ul>
            <li><a href='$loginLink'>$loginText</a></li>
NAVMENU;
    foreach ($links as $l => $l_value) {
        $navContent .= <<<NAVMENU
                <li><a href='$l'>$l_value</a></li>             
NAVMENU;
    }
    $navContent .= <<<NAVMENU
        </ul>
        </nav>
NAVMENU;

    return $navContent; // Returns the NavMenu to the client
}

function validate_form()
{ // This function validates and sanitizes form data so it is safe to upload to a database

    $input = array(); // Array to store inputs of form
    $errors = array(); // Array to store any errors from validation
    $catIDs = array(); // Array to store the catIDs for validation
    $manIDs = array(); // Array to store the manIDs for validation


    // Fetches the form inputs and checks to see if they have values entered if not they are set to null and if they do exist whitespace is trimmed
    $input['toyID'] = filter_has_var(INPUT_POST, 'toyID') ? $_POST['toyID'] : null;
    $input['toyID'] = trim($input['toyID']);
    $input['toyID'] = filter_var($input['toyID'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

    $input['toyName'] = filter_has_var(INPUT_POST, 'toyName') ? $_POST['toyName'] : null;
    $input['toyName'] = trim($input['toyName']);
    $input['toyName'] = filter_var($input['toyName'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $input['toyCategories'] = filter_has_var(INPUT_POST, 'toyCategories') ? $_POST['toyCategories'] : null;
    $input['toyCategories'] = trim($input['toyCategories']);

    $input['toyManufacturer'] = filter_has_var(INPUT_POST, 'toyManufacturer') ? $_POST['toyManufacturer'] : null;
    $input['toyManufacturer'] = trim($input['toyManufacturer']);

    $input['toyDescription'] = filter_has_var(INPUT_POST, 'toyDescription') ? $_POST['toyDescription'] : null;
    $input['toyDescription'] = trim($input['toyDescription']);
    $input['toyDescription'] = filter_var($input['toyDescription'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

    $input['toyPrice'] = filter_has_var(INPUT_POST, 'toyPrice') ? $_POST['toyPrice'] : null;
    $input['toyPrice'] = trim($input['toyPrice']);
    $input['toyPrice'] = filter_var($input['toyPrice'], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);


    // Checks to see if the form fields are empty/null if so an error message is stored in the array errors
    if (empty($input['toyID'])) {
        $errors[] = "<p>The toyID is missing, make sure the value has not been edited!</p>";
    }
    if (empty($input['toyName'])) {
        $errors[] = '<p>The toy name has not been entered!</p>';
    }
    if (empty($input['toyCategories'])) {
        $errors[] = '<p>The toy category has not been selected!</p>';
    }
    if (empty($input['toyManufacturer'])) {
        $errors[] = '<p>The toy manufacturer has not been selected!</p>';
    }
    if (empty($input['toyDescription'])) {
        $errors[] = '<p>The toy description has not been entered!!</p>';
    }
    if (empty($input['toyPrice'])) {
        $errors[] = '<p>The toy price has not been entered or was not a number!</p>';
    }


    try {
        $dbConn = getConnection();
        $sqlGetCategories = "SELECT catID, catDesc FROM NTL_category"; // Fetching the allowed catIDs from the database so we can sanitize data to only the correct entries.
        $sqlResultGetCategories = $dbConn->query($sqlGetCategories); // Executing the SQL query and converting it to a object.

        while ($categoriesRowObj = $sqlResultGetCategories->fetchObject()) { // Iterates through the object storing the catIDs into an array
            $catIDs[] = $categoriesRowObj->catID;
        }

        $sqlGetManufacturers = "SELECT manID, manName FROM NTL_manufacturer"; // Fetching the allowed manufacturer IDs from the database so we can sanitize data to only the correct entries.
        $sqlResultGetManufacturers = $dbConn->query($sqlGetManufacturers); // Executing the SQL query and converting it to a object.

        while ($manufacturersRowObj = $sqlResultGetManufacturers->fetchObject()) { // Iterates through the object storing the manIDs into an array
            $manIDs[] = $manufacturersRowObj->manID;
        }

    } catch (Exception $e) { // Catches any exception and displays a message to the user plus logs it in a file
        exceptionHandler($e);
    }

    // Checks to see if the entered catID and manID is equal to those within the database if not an error is added to the error array
    if (!in_array($input['toyCategories'], $catIDs)) {
        $errors[] = '<p>catID has been modified to something which is not permitted!</p>';
    }
    if (!in_array($input['toyManufacturer'], $manIDs)) {
        $errors[] = '<p>manID has been modified to something which is not permitted!</p>';
    }

    return array($input, $errors); // Returns an array containing the form inputs and any errors.

}

function prepareAndExecuteQuery($query, array $arraySubstitutes)
{ // Prepares and executes an SQL query

    try {

        $dbConn = getConnection(); // Connect to database
        $executeQuery = $dbConn->prepare($query); // Prepare query
        if ($executeQuery->execute($arraySubstitutes)) { // Execute query with substitued values
            return $executeQuery;
        } else {
            throw new Exception("An error has happened trying to execute a query!");
        }
    } catch (Exception $e) {
        exceptionHandler($e);
    }
}

function notLoggedIn()
{
    echo "<h3><a href='login.php'>You are not logged in! Click here to go to the login page</a></h3>";
}

?>
