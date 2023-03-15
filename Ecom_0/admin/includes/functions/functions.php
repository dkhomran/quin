<?php

/*
### function for title pages v1.0
*/
function getTitle()
{
    global $pageTitle;
    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo  "Admin Area";
    }
}


/*
### redirect function v1.0
*/
function redirectHome($Msg, $seconds = 2, $url = null, $page = "home")
{
    if ($url === null) {
        $url = 'index.php';
    }
    echo $Msg;
    echo "<div class='alert alert-info'>You Willl Be redirected to $page page afer $seconds seconds</div>";
    header("refresh: $seconds; url=$url");
    exit();
}


/*
### check exist in DB or not v1.0
-- param ($select, $from, $value)
*/
function checkItem($select, $from, $value)
{
    global $conn;
    $sqlFunction = "SELECT $select FROM $from WHERE $select = '$value'";
    $queryFunction = mysqli_query($conn, $sqlFunction);
    $num = mysqli_num_rows($queryFunction);
    return $num;
}

/* 
### calc item v1.0
### Function to calc number of item
*/

function calcItems($col, $table, $condition)
{
    global $conn;
    $sql = "SELECT COUNT($col) FROM $table $condition";
    $query = mysqli_query($conn, $sql);
    $num = mysqli_fetch_column($query);
    return $num;
}

/* 
### latest item v1.0
### Function to get  latest item
*/

function latest($col, $table, $order, $limit)
{
    global $conn;
    $sql = "SELECT $col FROM $table WHERE GroupID = 0 ORDER BY $order DESC LIMIT $limit";
    $query = mysqli_query($conn, $sql);
    $num = mysqli_num_rows($query);
    if ($num > 0) {
        return $query;
    } else {
        $msg = "there is no $table Found";
        return $msg;
    }
}

/* 
### DeleteInstall v1.0
### Function to Delete install file
*/

function DeleteInstall($i)
{
    if (file_exists($i)) {
        unlink($i);
    }
}
