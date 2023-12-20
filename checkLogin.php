<?php
require_once "functions.php";
startSession();
?>
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>Check Login</title>
</head>
<body>
<h1>Check Login</h1>
<?php
echo createNav('Navigation Menu', 'login.php', array(
    'index.php' => 'Homepage',
    'orderToysForm.php' => 'Order Toys',
    'selectToy.php' => 'Select a Toy to Edit',
    'credits.php' => 'Credits'
));
try{

    // Takes the user input from the form
    $username = filter_has_var(INPUT_POST, 'username')
        ? $_POST['username'] : null;
    $password = filter_has_var(INPUT_POST, 'password')
        ? $_POST['password'] : null;

    if(empty($username) || empty($password) ){
        echo "<p>Username or password has not been entered!</p>";
    }else {


        $username = filter_var($username, FILTER_SANITIZE_STRING);

        // Prepares and executes the SQL statement to check to see if the username exists
        $accountQueryObj = prepareAndExecuteQuery("SELECT username, passwordHash FROM NTL_users WHERE username = :username", array(':username' => $username));

        $accountRowObj = $accountQueryObj->fetchObject(); // fetch the account object

        if (!isset($accountRowObj->username)) { // Checks to see if an account with that username exists in the database
            echo "<p>Username or password is invalid!</p>";
            echo '</body></html>';
        } else {

            $hashedPassword = $accountRowObj->passwordHash;
            if (password_verify($password, $hashedPassword)) {
                $_SESSION['logged-in'] = 'true';
                header('Location: http://unn-w19011575.newnumyspace.co.uk/webprogramming/index.php');
            } else {
                echo "<p>Username or password is incorrect try again</p>";
                echo "</body></html>";
            }

        }
    }
}catch(Exception $e){
    exceptionHandler($e);
}
?>
</body>
</html>
