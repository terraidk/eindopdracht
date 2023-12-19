<?php
session_start();
if (!isset($_SESSION['loggedInAdmin'])) {
    header("Location: inloggen.php");
    session_destroy();
}
require_once('database.php');

$database = new Database();


// ... continue with admin panel code ...
?>