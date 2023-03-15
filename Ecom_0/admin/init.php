<?php
// start session 
session_start();

$rel_Path = "./";

// connect DB
include "" . $rel_Path . "connect.php";

// Routes
$tpl = "" . $rel_Path . "includes/templates/"; // templates directory
$css = "" . $rel_Path . "layout/css/"; // css directory
$js = "" . $rel_Path . "layout/js/"; // css directory
$lang = "" . $rel_Path . "includes/langs/"; // languages
$func = "" . $rel_Path . "includes/functions/"; // Functions

// include Functions
include $func . "functions.php"; // Functions
// include language
include $lang . "English.php"; // languages
// header 
include $tpl . "Header.php";

// include nav bar on all pages exept the login page (index.php)

if (!isset($noNavbar)) {
    include $tpl . "Navbar.php";
}

// login verification
if (!isset($notLogin)) {
    if (!isset($_SESSION['user'])) {
        header('location: index.php');
    }
}
