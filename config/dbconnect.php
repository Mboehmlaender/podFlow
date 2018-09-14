<?php
include('dbsettings.php');
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysqli_select_db($con,DB_NAME);

if ($con->connect_error) {
    die('Connect Error (' . $con->connect_errno . ') '
            . $con->connect_error);
}
?>
