<?php
ob_start();
$pageTitle = "Categories";
include 'init.php';
/*
** Manage categorie
** (add - edit - delete)
*/
?>


<?php
$do = isset($_GET['do']) ? $_GET['do'] : 'manage';

switch ($do) {
    case 'manage':
        $sql2 = "SELECT * FROM categories";
        $query2 = mysqli_query($conn, $sql2);
        $num = mysqli_num_rows($query2);
        echo "      
          <h1 class='text-center p-5'>manage categories</h1>
          <div class='container mb-5'>";


        if (isset($_SESSION['updated'])) {
            echo $_SESSION['updated'];
            unset($_SESSION['updated']);
        }
        echo "
            <div class='input-group mb-2'>
                <div class='form-outline'>
                    <input placeholder='search' type='search' id='form1' class='form-control' />
                </div>
                <button type='button' class='btn btn-primary'>
                    <i class='fas fa-search'></i>
                </button>
            </div>
            <div class='card col-lg col-sm-12'>
                <div class='card-header bg-dark text-light '>
                    manage categories
                </div>
                <div class='card-body'>";
        if ($num == 0) :
            echo "
                        <ul class='latest-items'>
                            <li class='latest-item'>
                                There is no category added yet !        
                            </li>
                        </ul>
                    ";
        else :
            echo "
                    <ul class='latest-items'>";
            while ($rows = mysqli_fetch_array($query2)) :
                echo " <li class='catItem latest-item'>";
                echo "<div>";
                echo "<h4>Name : " . $rows["Name"] . "</h4>";
                echo "<p>Description : " . $rows["Description"] . "</p>";
                if ($rows["Visibility"] == 1) :
                    echo "<p class='text-success'>visible</p>";
                else :
                    echo "<p class='text-danger'>not visible</p>";
                endif;
                if ($rows["Allow_comnt"] == 1) :
                    echo "<p class='text-success'>comment allowed</p>";
                else :
                    echo "<p class='text-danger'>comment not allowed</p>";
                endif;
                if ($rows["Allow_ads"] == 1) :
                    echo "<p class='text-success'>ads allowed</p>";
                else :
                    echo "<p class='text-danger'>ads not allowed</p>";
                endif;
                echo "</div>";
                echo "<div class='cat-btns'>";
                echo "<a href='categories.php?do=edit&id=" . $rows['CatID'] . "' class='btn btn-success btn-sm d-block m-2'>update</a>";
                echo "<a href='categories.php?do=delete&id=" . $rows['CatID'] . "' class='btn btn-danger btn-sm d-block m-2'>delete</a>";
                echo "</div>";

                echo "  </li>";
            endwhile;

            echo "</ul>";
        endif;
        echo " </div>
            </div>
            <a href='categories.php?do=add' class='btn btn-primary mt-2 btn-sm'><i class='fa fa-plus'></i> add category</a>
        </div>
        ";

        break;

    case 'add':
        echo "
        <h1 class='text-center p-5'>Add New Categorie</h1>
        <div class='container'>
            <form class='form-horizontal form-edit-members' action='?do=insert' method='post'>
                <div class='mb-3 col-sm-10  col-md-4 form-wrapper'>
                    <label for='name' class='form-label fw-semibold'>Name</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='Name of the categorie' id='name' type='text' name='name' class='form-control' required>
                    ";
        if (isset($_SESSION['emptyname'])) {
            echo "<div class='text-danger ps-2'>" . $_SESSION['emptyname'] . "</div>";
            unset($_SESSION['emptyname']);
        }

        echo "</div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='description' class='form-label fw-semibold'>description</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='Description of the categorie' type='text' name='description' class=' form-control' id='description'>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='ordering' class='form-label fw-semibold'>Ordering</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='number to order the categorie' type='number' name='ordering' class='form-control' id='ordering'>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label class='form-label fw-semibold'>Visibility</label>
                    <select class='form-select' name='visibility'>
                        <option value='1' selected>Yes</option>
                        <option value='0'>No</option>
                    </select>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label class='form-label fw-semibold'>Allow Commenting</label>
                    <select class='form-select' name='commenting'>
                        <option value='1' selected>Yes</option>
                        <option value='0'>No</option>
                    </select>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label class='form-label fw-semibold'>Allow Ads</label>
                    <select class='form-select' name='ads'>
                        <option value='1' selected>Yes</option>
                        <option value='0'>No</option>
                    </select>
                </div>
                <div class='col-md-4'>
                    <button type='submit' class='btn btn-success w-100'>add</button>
                </div>
            </form>
        </div>";
        break;

    case 'insert':
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $name = $_POST['name'];
            $description = $_POST['description'];
            $ordering = $_POST['ordering'] ? $_POST['ordering'] : 0;
            $visibility = $_POST['visibility'];
            $commenting = $_POST['commenting'];
            $ads = $_POST['ads'];

            $checkname = 0;

            if (empty($name)) {
                $checkname = 1;
            }

            if ($checkname == 1) {
                $_SESSION['emptyname'] = "You can't add category with empty name";
                header("location: categories.php?do=add");
            } else {
                $CatExist = checkItem('Name', 'categories', $name);
                if ($CatExist == 1) :
                    echo "<div class='container mt-5'>";
                    $Msg = "<div class='alert alert-danger'>You cant't add the category with this Name ! it's alerady Exist ! </div>";
                    redirectHome($Msg, 2, 'categories.php?do=add', 'categorie');
                    echo "</div>";

                else :
                    $sql = "INSERT INTO categories 
                    (Name, Description, Ordering, Visibility,Allow_comnt,Allow_ads) 
                    VALUES ('$name','$description',$ordering,$visibility,$commenting,$ads)";
                    $query = mysqli_query($conn, $sql);
                    if ($query) {
                        header('location:categories.php');
                    }
                endif;
            }
        } else {
            echo "<div class='container mt-5'>";
            $Msg = "<div class='alert alert-danger'>You can't access directly ! </div>";
            redirectHome($Msg, 3, 'categories.php?do=add', 'categorie');
            echo "</div>";
        }
        break;
    case 'edit': // edit CATEGORY page
        if (isset($_GET['id']) && is_numeric($_GET['id'])) :
            $id = intval($_GET['id']);
        else :
            $id = 0;
        endif;

        $sql3 = "SELECT * FROM categories 
            WHERE CatID = $id LIMIT 1";
        $query3 = mysqli_query($conn, $sql3);
        $num3 = mysqli_num_rows($query3);
        if ($num3 === 1) :
            $rows = mysqli_fetch_array($query3);
            echo "
                <h1 class='text-center p-5'>Edit category</h1>
                <div class='container'>
                    <form class='form-horizontal form-edit-members' action='?do=update' method='post'>
                       <input type='number' name='id' value='" . $rows['CatID'] . "' hidden/>
                        <div class='mb-3 col-sm-10  col-md-4 form-wrapper'>
                            <label for='name' class='form-label fw-semibold'>name</label>
                            <input id='name' value='" . $rows['Name'] . "' type='text' name='name' class='form-control' required>
                        </div>
    
                        <div class='mb-3 col-md-4 form-wrapper'>
                            <label for='description' class='form-label fw-semibold'>description</label>
                            <input type='text' name='description' value='" . $rows['Description'] . "' class=' form-control' id='description'>
                        </div>
    
                        <div class='mb-3 col-md-4 form-wrapper'>
                            <label for='ordering' class='form-label fw-semibold'>ordering</label>
                            <input type='number' name='ordering' value='" . $rows['Ordering'] . "' class='form-control' id='ordering'>
                        </div>
    
                        <div class='col-md-4'>
                            <button type='submit' class='btn btn-success w-100'>update</button>
                        </div>
                    </form>
                </div>";
        else :
            echo "<div class='container mt-5'>";
            $Msg = "<div class='alert alert-danger'>there is no id like that</div>";
            $url = "members.php?do=edit";
            redirectHome($Msg, 2, $url);
            echo "<div>";
        endif;
        break;
    case 'update': // update CATEGORY in DB
        echo "
                <div class='container'>
                <h1 class='text-center p-5'>update category</h1>
            ";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            // get info from edit page
            $id         = $_POST['id'];
            $name   = $_POST['name'];
            $description      = $_POST['description'];
            $ordering      = $_POST['ordering'];

            // Validation of info
            $errors = array();
            if (empty($name)) :
                $errors[] = "name can't be empty";
            endif;

            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }


            if (empty($errors)) :

                $check = checkItem("Name", "categories",  $name);

                if ($check ==  1) :
                    $Msg = "<div class='alert alert-warning'>You can't use this name ! it's already Exist !</div>";
                    $url = "categories.php";
                    $page = "the category";
                    redirectHome($Msg, 3, $url, $page);
                else :
                    // update DB with this info
                    $sql5 = "UPDATE categories 
                        SET Name = '$name' , Description = '$description' , Ordering =  '$ordering'
                        WHERE CatID = $id";
                    $query5 = mysqli_query($conn, $sql5);

                    if ($query5) :
                        $_SESSION['updated'] = "<div class='alert alert-success'>Category updated successfully</div>";
                        header('location: categories.php');
                    endif;
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

    case 'delete':

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            $id = 0;
        }

        if ($id == 0) {
            echo "<div class='container mt-5'>";
            $Msg = "<div class='alert alert-danger'>You can't access directly ! </div>";
            redirectHome($Msg, 3, 'categories.php', 'categorie');
            echo "</div>";
        } else {

            $checkcat = checkItem('CatID', "categories", $id);
            if ($checkcat == 1) :
                $sql3 = "DELETE FROM categories WHERE CatID = $id";
                $query3 = mysqli_query($conn, $sql3);
                if ($query3) :
                    header('location:categories.php');
                endif;
            else :
                echo "<div class='container mt-5'>";
                $Msg = "<div class='alert alert-danger'>Categorie don't exist </div>";
                redirectHome($Msg, 3, 'categories.php', 'categorie');
                echo "</div>";
            endif;
        }

        break;

    default:
        echo "<div class='container mt-5'>";
        $Msg = "<div class='alert alert-danger'>Sorry Page Not Found</div>";
        redirectHome($Msg, 3);
        echo "</div>";
        break;
}



?>

<?php
include "./includes/templates/Footer.php";
ob_end_flush();
?>