<?php
$pageTitle = "Dashboard";
include 'init.php';
?>

<?php
// total members
$col = "UserID";
$table = "users";
$condition = "WHERE GroupID = 0 and RegStatus = 1";
$num = calcItems($col, $table, $condition);

// pending members
$col = "UserID";
$table = "users";
$condition = "WHERE RegStatus = 0";
$num2 = calcItems($col, $table, $condition);

// latest members 
$limit = 5;
$col = '*';
$table = "users";
$order = 'UserID';
$queryLatest = latest($col, $table, $order, $limit);
?>


<main class="dash">
    <div class="container">
        <h1 class='text-center p-5'>Dashboard</h1>
        <!-- latest registered user -->
        <div class="cards row gap-2 m-1">
            <div class="card text-white bg-success  col-md ">
                <div class="card-header text-center">Payed members</div>
                <div class="card-body">
                    <h5 class="card-title text-center"><a href="members.php"><?php echo $num; ?></a></h5>
                </div>
            </div>
            <div class="card text-white bg-danger  col-md ">
                <div class="card-header text-center">Credit members ...</div>
                <div class="card-body">
                    <h5 class="card-title text-center"><a href="members.php?do=manage&&page=pending"><?php echo $num2; ?></a></h5>
                </div>
            </div>
            <div class="card text-white bg-warning  col-md ">
                <div class="card-header text-center">Total members</div>
                <div class="card-body">
                    <h5 class="card-title text-center">120</h5>
                </div>
            </div>
            <div class="card text-white bg-success  col-md ">
                <div class="card-header text-center">Total members</div>
                <div class="card-body">
                    <h5 class="card-title text-center">120</h5>
                </div>
            </div>
        </div>

        <!-- latest Items added -->
        <div class="dash-2 row gap-2 m-1">
            <div class="card col-lg col-sm-12">
                <div class="card-header bg-dark text-light ">
                    Latest <?php echo $limit ?> Registred Users
                </div>
                <div class="card-body">
                    <?php
                    echo "<ul class='latest-items'>";
                    if (gettype($queryLatest) == 'object') :
                        while ($rows = mysqli_fetch_array($queryLatest)) {
                            if ($rows['RegStatus'] == 0) :
                                echo "<li class='latest-item'>
                                <h6>" . $rows['Username'] . "</h6>
                                <div>
                                <a href='members.php?do=approve&id=" . $rows['UserID'] . "&page=dash' class='btn btn-info text-light btn-sm'><i class='fa fa-check'></i>Approve</a>                            
                                <a href='members.php?do=delete&id=" . $rows['UserID'] . "&page=dash' class='btn btn-danger text-light btn-sm confirm'><i class='fa fa-close'></i>ADelete</a>                            
                                </div>
                                </li>";
                            else :
                                echo "<li class='latest-item'>
                                <h6>" . $rows['Username'] . "</h6>
                                <div>
                                <a href='members.php?do=delete&id=" . $rows['UserID'] . "&page=dash' class='btn btn-danger text-light btn-sm'>Delete</a>                            
                                </div>
                                </li>";
                            endif;
                        }
                    else :
                        echo "<h4>" . $queryLatest . "</h4>";
                    endif;
                    echo "</ul>";

                    ?>

                </div>
            </div>
            <div class="card col-lg col-sm-12">
                <div class="card-header bg-dark text-light ">
                    Latest <?php echo $limit ?> Items Added
                </div>
                <div class="card-body">
                    <?php
                    echo "<ul class='latest-items'>";
                    if (gettype($queryLatest) == 'object') :
                        while ($rows = mysqli_fetch_array($queryLatest)) {
                            if ($rows['RegStatus'] == 0) :
                                echo "<li class='latest-item'>
                                <h6>" . $rows['Username'] . "</h6>
                                <div>
                                <a href='members.php?do=approve&id=" . $rows['UserID'] . "&page=dash' class='btn btn-info text-light btn-sm'><i class='fa fa-check'></i>Approve</a>                            
                                <a href='members.php?do=delete&id=" . $rows['UserID'] . "&page=dash' class='btn btn-danger text-light btn-sm confirm'><i class='fa fa-close'></i>ADelete</a>                            
                                </div>
                                </li>";
                            else :
                                echo "<li class='latest-item'>
                                <h6>" . $rows['Username'] . "</h6>
                                <div>
                                <a href='members.php?do=delete&id=" . $rows['UserID'] . "&page=dash' class='btn btn-danger text-light btn-sm'>Delete</a>                            
                                </div>
                                </li>";
                            endif;
                        }
                    else :
                        echo "<h4>" . $queryLatest . "</h4>";
                    endif;
                    echo "</ul>";

                    ?>

                </div>
            </div>
        </div>
    </div>
</main>


<?php
include "./includes/templates/Footer.php";
?>