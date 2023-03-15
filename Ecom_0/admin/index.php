<?php
$noNavbar = '';
$notLogin = "";
$pageTitle = "Login";
include 'init.php';

if (isset($_SESSION['user'])) :
    header('location: dashboard.php');
endif;
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') :
    // Get info From inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashPass = md5($password);

    // Query
    $sql = "SELECT UserID,username, password FROM users
    WHERE username = '$username' AND password = '$hashPass' AND GroupID = 1";
    $query = mysqli_query($conn, $sql);

    // check user(admin) exist or not
    $num = mysqli_num_rows($query);
    $rows = mysqli_fetch_array($query);
    if ($num === 1) :
        $_SESSION['user'] = $username; // register session name
        $_SESSION['id'] = $rows['UserID']; // pass session id
        header("location: dashboard.php"); // redirect to dashboard admin
        exit();
    else :
        $_SESSION['failed'] = "<span class='d-block text-center w-100 text-danger'>Username or Password Incorrect</span>";
        header('location: ' . $_SERVER['PHP_SELF']);
        exit();
    endif;
endif;
?>



<form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <?php if (isset($_SESSION['failed'])) {
        echo $_SESSION['failed'];
        unset($_SESSION['failed']);
    } ?>
    <h2 class="text-center">Login Admin</h2>

    <div>
        <input onblur="blurPlace(this)" onfocus="focusPlace(this)" id="username" type="text" name="username" class="form-control mb-2" placeholder="Username" autocomplete="off">
    </div>
    <div class="pass-wrapper">
        <input title='pass' onblur="blurPlace(this)" onfocus="focusPlace(this)" id="password" type="password" name="password" class="form-control mb-2" placeholder="Password" autocomplete="off">
    </div>

    <input type="submit" class="btn btn-success w-100" value="Sign in" />
</form>



<?php
include "./includes/templates/Footer.php";
?>