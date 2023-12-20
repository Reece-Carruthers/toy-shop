<?php
require_once 'functions.php';
startSession();
?>
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>Login</title>
    </head>
<body>
<?php
if (checkLoginStatus()) { // if login status is true
    ?>
    <h1>Click the button to logout!</h1>
    <?php
    echo createNav('Navigation Menu', 'login.php', array(
        'index.php' => 'Homepage',
        'orderToysForm.php' => 'Order Toys',
        'selectToy.php' => 'Select a Toy to Edit',
        'credits.php' => 'Credits'
    ));
    ?>
    <form id='logoutform' action='logout.php'>
        <input type='submit' value='Logout'>
    </form>
    </body>
    </html>
    <?php
} else {
    ?>
    <h1>Login to access admin features</h1>
    <?php
    echo createNav('Navigation Menu', 'login.php', array(
        'index.php' => 'Homepage',
        'orderToysForm.php' => 'Order Toys',
        'selectToy.php' => 'Select a Toy to Edit',
        'credits.php' => 'Credits'
    ));
    ?>
    <form id="loginform" action="checkLogin.php" method="post">
        <label>Username</label>
        <input type="text" name="username">
        <br><br>
        <label>Password</label>
        <input type="password" name="password">
        <br><br>
        <input type="submit" value="Login">
    </form>
    </body>
    </html>
    <?php
}
?>