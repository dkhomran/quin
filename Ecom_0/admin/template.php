<?php
ob_start();
$pageTitle = "";
include 'init.php';
/*
** Manage Members
** (add - edit - delete)
*/
?>


<?php
$do = isset($_GET['do']) ? $_GET['do'] : "manage";

switch ($do) {
    case 'manage': // manage members page
        break;

    case 'add': // add member page
        break;

    case 'insert': // insert member page to DB
        break;


    case 'edit': // edit member page
        break;


    case 'update': // update member in DB
        break;

    case  'delete':
        break;

    default:
        echo "<div class='container mt-5 text-center'>";
        $Msg = "<div class='alert alert-danger'>PAGE NOT FOUND 404</div>";
        $url = "members.php";
        redirectHome($Msg, 4);
        echo "<div>";
        break;
}
?>

<?php
include "./includes/templates/Footer.php";
ob_end_flush();
?>


