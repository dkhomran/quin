<?php
ob_start();
$pageTitle = "manage members";
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

        $check = "";
        $title = "Payment of Members";
        $msg = "There is no payment added yet";
        if (isset($_GET['page'])  && $_GET['page'] == "pending") {
            $check = "AND RegStatus = 0";
            $title = "Credit of Members";
            $msg = "There is no credit added yet";
            $pending = "";
        }

        $sql3 = "SELECT * FROM users WHERE GroupID != 1 $check";
        $query3 = mysqli_query($conn, $sql3);
        $num = mysqli_num_rows($query3);
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
        echo "
            <div class='table-responsive'>
            <div class='mb-2 d-flex justify-content-between'>
            <a href='?do=manage&&page=pending' type='button' class='btn btn-danger'>Credit of members</a>
            <a href='?do=manage' type='button' class='btn btn-success'>members payed</a>
            </div>
                <table class='main-table table table-bordered'>
                    <thead class='text-center'>
                        <td>ID</td>
                        <td>Nom et Prenom</td>
                        <td>Num Tel</td>
                        <td>Full name</td>";
        if (!isset($pending)) {
            echo "<td>Date of Payment</td>";
        } else {
            echo "<td>Date of Credit</td>";
        }
        echo "<td>Control</td>
                    </thead>";
        if ($num > 0) :
            $i = 1;
            while ($rows = mysqli_fetch_array($query3)) :
                echo "
                <tr class='text-center'>
                <td>" . $i . " </td>
                <td> " . $rows['Username'] . "</td>
                <td>" . $rows['Email'] . " </td>
                <td>" . $rows['FullName'] . " </td>
                <td> " . $rows['date'] . " </td>
                <td>
                    <a href='members.php?do=edit&id=" . $rows['UserID'] . "&page=members' class='btn btn-success btn-sm'><i class='fa fa-edit '></i>edit</a>
                    <a href='members.php?do=delete&id=" . $rows['UserID'] . "&page=members' class='btn btn-danger btn-sm confirm'><i class='fa fa-close '></i>delete</a>";
                if ($rows['RegStatus'] == 0) {
                    echo " <a href='members.php?do=approve&id=" . $rows['UserID'] . "&page=members' class='btn btn-info text-light btn-sm'><i class='fa fa-check '></i>approve payment</a>";
                }

                echo "</td>
            </tr>";
                $i++;
            endwhile;
        else :

            echo "<div class='alert alert-warning text-center'>$msg</div>";
        endif;

        echo "     
                </table>
            </div>";
        if (isset($pending)) {
            echo "<a href='members.php?do=add' class='btn btn-primary mt-2 btn-sm'><i class='fa fa-plus'></i> add credit member</a>";
        }
        ";
        </div>";
        break;

    case 'add': // add member page
        echo "
        <h1 class='text-center p-5'>Add New Member</h1>
        <div class='container'>
            <form class='form-horizontal form-edit-members' action='?do=insert' method='post'>
                <div class='mb-3 col-sm-10  col-md-4 form-wrapper'>
                    <label for='username' class='form-label fw-semibold'>nom et prenom</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='username to login' id='username' type='text' name='username' class='form-control' required>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='fname' class='form-label fw-semibold'>Full name</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='valid full name' type='text' name='fname' class=' form-control' id='fname'  required>
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='email' class='form-label fw-semibold'>Email</label>
                    <input onclick='focusPlace(this)' onblur='blurPlace(this)' placeholder='email must be valid' type='email' name='email' class='form-control' id='email' required >
                </div>

                <div class='mb-3 col-md-4 form-wrapper'>
                    <label for='Password' class='form-label fw-semibold'>Password</label>
                    <div class='pass-wrapper'>
                        <input placeholder='password must be strong' title='pass' onfocus='focusPlace(this)' onblur='blurPlace(this)' type='password' name='password'  class='form-control' id='Password' autocomplete='new-password'>
                    </div>
                </div>

                <div class='col-md-4'>
                    <button type='submit' class='btn btn-success w-100'>add</button>
                </div>
            </form>
        </div>";
        break;

    case 'insert': // insert member page to DB

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            echo "
            <div class='container'>
            <h1 class='text-center p-5'>add new member</h1>
            ";
            // get info from edit page
            $username = $_POST['username'];
            $fname = $_POST['fname'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $hashPass = md5($pass);

            // Validation of info
            $errors = array();
            if (empty($username)) :
                $errors[] = "Usernname can't be empty";
            endif;
            if (empty($email)) :
                $errors[] = "Email can't be empty";
            endif;

            if (empty($fname)) :
                $errors[] = "Full name can't be empty";
            endif;

            if (empty($pass)) :
                $errors[] = "Password can't be empty";
            elseif (strlen($pass) < 6) :
                $errors[] = "Password must be more than 6 characters";
            endif;

            // handle errors (show)
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }


            if (empty($errors)) :
                $check = checkItem("Username", "users",  $username);

                if ($check == 1) :
                    $Msg = "<div class='alert alert-warning'>You can't use this Username ! it's already Exist !</div>";
                    redirectHome($Msg);
                else :
                    // insert info into in DB 
                    $sql2 = "INSERT INTO users (Username, Password, Email,FullName,RegStatus,date)
                    VALUES('$username','$hashPass','$email','$fname',0,now())";
                    $query2 = mysqli_query($conn, $sql2);

                    if ($query2) :
                        $_SESSION['insert'] = "<div class='alert alert-success'>User inserted successfully</div>";
                        header('location: members.php');
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


    case 'edit': // edit member page
        if (isset($_GET['id']) && is_numeric($_GET['id'])) :
            $id = intval($_GET['id']);
        else :
            $id = 0;
        endif;

        $sql = "SELECT * FROM users 
        WHERE UserID = $id LIMIT 1";
        $query = mysqli_query($conn, $sql);
        $num = mysqli_num_rows($query);
        if ($num === 1) :
            $rows = mysqli_fetch_array($query);
            echo "
            <h1 class='text-center p-5'>Edit member</h1>
            <div class='container'>
                <form class='form-horizontal form-edit-members' action='?do=update' method='post'>
                   <input type='number' name='id' value='" . $rows['UserID'] . "' hidden/>
                    <div class='mb-3 col-sm-10  col-md-4 form-wrapper'>
                        <label for='username' class='form-label fw-semibold'>Username</label>
                        <input id='username' value='" . $rows['Username'] . "' type='text' name='username' class='form-control' required>
                    </div>

                    <div class='mb-3 col-md-4 form-wrapper'>
                        <label for='fname' class='form-label fw-semibold'>Full name</label>
                        <input type='text' name='fname' value='" . $rows['FullName'] . "' class=' form-control' id='fname' required>
                    </div>

                    <div class='mb-3 col-md-4 form-wrapper'>
                        <label for='email' class='form-label fw-semibold'>Email</label>
                        <input type='email' name='email' value='" . $rows['Email'] . "' class='form-control' id='email' required>
                    </div>

                    <div class='mb-3 col-md-4 form-wrapper'>
                        <label for='Password' class='form-label fw-semibold'>Password</label>
                        <input type='text' name='oldpassword' value='" . $rows['Password'] . "' hidden>
                        <div class='pass-wrapper'>
                            <input title='pass' onfocus='focusPlace(this)' onblur='blurPlace(this)' type='password' name='newpassword' placeholder='Leave it empty to keep old password'  class='form-control' id='Password' autocomplete='new-password'>
                        </div>
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


    case 'update': // update member in DB
        echo "
            <div class='container'>
            <h1 class='text-center p-5'>update member</h1>
        ";

        if ($_SERVER['REQUEST_METHOD'] == 'POST') :
            // get info from edit page
            $id         = $_POST['id'];
            $username   = $_POST['username'];
            $email      = $_POST['email'];
            $fname      = $_POST['fname'];

            // check password
            $pass = '';
            if (empty($_POST['newpassword'])) :
                $pass = $_POST['oldpassword'];
            else :
                $pass = md5($_POST['newpassword']);
            endif;

            // Validation of info
            $errors = array();
            if (empty($username)) :
                $errors[] = "Usernname can't be empty";
            endif;
            if (empty($email)) :
                $errors[] = "Email can't be empty";
            endif;
            if (empty($fname)) :
                $errors[] = "Full name can't be empty";
            endif;

            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>" . $error . "</div>";
            }


            if (empty($errors)) :

                $check = checkItem("Username", "users",  $username);

                if ($check ==  1) :
                    $Msg = "<div class='alert alert-warning'>You can't use this Username ! it's already Exist !</div>";
                    $url = "members.php";
                    $page = "the member";
                    redirectHome($Msg, 3, $url, $page);
                else :
                    // update DB with this info
                    $sql2 = "UPDATE users 
                    SET Username = '$username' , Email = '$email' , FullName =  '$fname', Password='$pass'
                    WHERE UserID = $id";
                    $query2 = mysqli_query($conn, $sql2);

                    if ($query2) :
                        $_SESSION['updated'] = "<div class='alert alert-success'>User updated successfully</div>";
                        header('location: members.php');
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

    case  'delete':
        echo "<div class='container mt-5'>";
        echo "<h1 class='text-center p-5'>Delete member</h1>";
        if (isset($_GET['id']) && is_numeric($_GET['id'])) :
            $id = intval($_GET['id']);
        else :
            $id = 0;
        endif;

        if ($id == 0) {
            echo "<div class='container mt-5 text-center'>";
            $Msg = "<div class='alert alert-danger'>PAGE NOT FOUND 404</div>";
            redirectHome($Msg, 4);
            echo "<div>";
        } else {
            $num = checkItem('UserID', 'users', $id);
            if ($num === 1) :
                $sql5 = "DELETE FROM users WHERE UserID = $id";
                $query5 = mysqli_query($conn, $sql5);
                if ($query5) :
                    if (isset($_GET['page'])) :
                        if ($_GET['page'] == 'members') :
                            $_SESSION['deleted'] = "<div class='alert alert-success'>User deleted</div>";
                            header('location: members.php');
                        elseif ($_GET['page'] == 'dash') :
                            $_SESSION['deleted'] = "<div class='alert alert-success'>User deleted</div>";
                            header('location: dashboard.php');
                        endif;
                    endif;
                endif;
            else :
                header('location: members.php');
            endif;
        }
        echo "</div>";
        break;

    case 'approve':
        echo "<div class='container mt-5'>";
        echo "<h1 class='text-center p-5'>Approve member</h1>";
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id = intval($_GET['id']);
        } else {
            $id = 0;
        }

        if ($id == 0) {
            $Msg = "<div class='alert alert-danger'>PAGE NOT FOUND 404</div>";
            redirectHome($Msg, 2, "members.php");
        } else {
            $num = checkItem('UserID', 'users', $id);
            if ($num === 1) :
                $sql5 = "UPDATE users SET RegStatus = 1, date=now() WHERE UserID = $id";
                $query5 = mysqli_query($conn, $sql5);
                if ($query5) :
                    $_SESSION['approved'] = "<div class='alert alert-success'>User approved</div>";
                    header('location: members.php');
                endif;
            else :
                header('location: members.php');
            endif;
        }


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