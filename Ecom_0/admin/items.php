<?php
ob_start();
$pageTitle = "items";
include 'init.php';
/*
** Manage Members
** (add - edit - delete)
*/
?>


<?php
$do = isset($_GET['do']) ? $_GET['do'] : "manage";

switch ($do) {
    case 'manage': // manage item page
        $title = "manage items";
        $sql4 = "SELECT * FROM items";
        $sql6 = "SELECT * FROM items";
        $query4 = mysqli_query($conn, $sql4);
        $query6 = mysqli_query($conn, $sql4);
        $num = mysqli_num_rows($query4);
        echo "
        <h1 class='text-center p-5'>$title</h1>
        <div class='container'>";
        if (isset($_SESSION['deleted'])) {
            echo $_SESSION['deleted'];
            unset($_SESSION['deleted']);
        };
        if (isset($_SESSION['updated'])) {
            echo $_SESSION['updated'];
            unset($_SESSION['updated']);
        };
        if (isset($_SESSION['insert'])) {
            echo $_SESSION['insert'];
            unset($_SESSION['insert']);
        };
        if (!isset($_GET['filterd'])) {
            echo "
        <div class='input-group mb-2'>
            <div class='form-outline'>
            <input class='form-control' list='datalistOptions' id='exampleDataList' placeholder='Type to search...'>
            <datalist id='datalistOptions'>";
            while ($rows = mysqli_fetch_array($query4)) {
                $name = $rows['name'];
                echo "<option value='" . $name . "'>";
            }

            echo "</datalist>
    </div>
    <a href='?filterd=" . $name . "' type='button' class='btn btn-primary'>
        <i class='fas fa-search'></i>
    </a>
</div>";
        }

        echo "  <div class='table-responsive'>
                <table class='main-table table table-bordered'>
                    <thead class='text-center'>
                        <td>ID</td>
                        <td>name</td>
                        <td>description</td>
                        <td>price</td>
                        <td>date</td>";
        echo "<td>Control</td>
                    </thead>";
        if ($num > 0 && !isset($_GET['filterd'])) :
            $i = 1;
            while ($rows2 = mysqli_fetch_array($query6)) :

                echo "
                <tr class='text-center'>
                <td>" . $i . " </td>
                <td> " . $rows2['name'] . "</td>
                <td>" . $rows2['description'] . " </td>
                <td>" . $rows2['price'] . " DT </td>
                <td> " . $rows2['add_date'] . " </td>
                <td>
                    <a href='items.php?do=edit&id=" . $rows2['id'] . "' class='btn btn-success btn-sm'><i class='fa fa-edit '></i>edit</a>
                    <a href='items.php?do=delete&id=" . $rows2['id'] . "' class='btn btn-danger btn-sm confirm'><i class='fa fa-close '></i>delete</a>";
                echo "</td>
            </tr>";
                $i++;
            endwhile;
        elseif (isset($_GET['filterd'])) :
            $name = $_GET['filterd'];
            $sql7 = "SELECT * FROM items WHERE name = '$name'";
            $query7 = mysqli_query($conn, $sql7);

            while ($rows3 = mysqli_fetch_array($query7)) :

                echo "
                <tr class='text-center'>
                <td>" . $rows3['id'] . " </td>
                <td> " . $rows3['name'] . "</td>
                <td>" . $rows3['description'] . " </td>
                <td>" . $rows3['price'] . " DT </td>
                <td> " . $rows3['add_date'] . " </td>
                <td>
                    <a href='items.php?do=edit&id=" . $rows3['id'] . "' class='btn btn-success btn-sm'><i class='fa fa-edit '></i>edit</a>
                    <a href='items.php?do=delete&id=" . $rows3['id'] . "' class='btn btn-danger btn-sm confirm'><i class='fa fa-close '></i>delete</a>";
                echo "</td>
            </tr>";
            endwhile;
        else :
            $msg = "There is no item added yet";
            echo "<div class='alert alert-warning text-center'>$msg</div>";
        endif;

        echo "     
                </table>
                <a href='items.php?do=add' class='btn btn-primary mt-2 btn-sm'><i class='fa fa-plus'></i> add item</a>
            </div>";

        ";
        </div>";


        break;

    case 'add': // add item page
        echo "
        <h1 class='text-center p-5'>Add New item</h1>
        <div class='container'>
            <form class='form-horizontal form-edit-members' action='?do=insert' method='post' enctype=''>
                <div class='mb-3 col-sm-10  col-md-4 form-wrapper'>
                    <label for='name' class='form-label fw-semibold'>Name</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='Name of the item' id='name' type='text' name='name' class='form-control' required>
                    ";
        if (isset($_SESSION['emptyname'])) {
            echo "<div class='text-danger ps-2'>" . $_SESSION['emptyname'] . "</div>";
            unset($_SESSION['emptyname']);
        }

        echo "</div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='description' class='form-label fw-semibold'>description</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='Description of the item' type='text' name='description' class=' form-control' id='description'>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='price' class='form-label fw-semibold'>price</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='price of the item' type='number' name='price' class='form-control' id='price' required>
                </div>
                <div class='mb-3 col-md-4 form-wrapper'>
                    <label class='form-label fw-semibold'>category</label>
                    <select class='form-select' name='cat' required>
                        <option value='0'>...</option>";
        $sql3 = "SELECT * FROM categories";
        $query3 = mysqli_query($conn, $sql3);

        while ($rows = mysqli_fetch_array($query3)) :
            echo "<option value='" . $rows['CatID'] . "'>" . $rows['Name'] . "</option>";
        endwhile;
        echo " </select>
                </div>
                <div class='col-md-4'>
                    <button type='submit' class='btn btn-success w-100'>add</button>
                </div>
            </form>
        </div>";
        break;

    case 'insert': // insert item page to DB
        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            echo "
            <div class='container'>
            <h1 class='text-center p-5'>add new member</h1>
            ";
            // get info from edit page
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $cat = $_POST['cat'];

            // Validation of info
            $errors = array();

            if (empty($name)) :
                $errors[] = "name can't be empty";
            endif;

            if (empty($price)) :
                $errors[] = "price can't be empty";
            endif;

            if (empty($cat)) :
                $errors[] = "category can't be empty";
            endif;

            // handle errors (show)
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }


            if (empty($errors)) :
                // insert info into in DB 
                $sql2 = "INSERT INTO items (name, description,price, add_date,CatID)
                    VALUES('$name','$description','$price',now(),$cat)";
                $query2 = mysqli_query($conn, $sql2);

                if ($query2) :
                    $_SESSION['insert'] = "<div class='alert alert-success'>item inserted successfully</div>";
                    header('location: items.php');
                endif;
            endif;

        else :
            echo "<div class='container mt-5'>";
            $Msg = "<div class='alert alert-danger'>Sorry you can't access to this page directly</div>";
            redirectHome($Msg, 4);
            echo "<div>";
        endif;
        echo "</div>";
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


