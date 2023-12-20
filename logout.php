<?php
require_once "functions.php";
startSession();
$_SESSION['logged-in'] = 'false'; // logout
header('Location: http://unn-w19011575.newnumyspace.co.uk/webprogramming/login.php');
?>