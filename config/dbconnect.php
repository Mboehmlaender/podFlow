/*********************************************************************
    Michael Böhmländer <info@podflow.de>
    Copyright (c)  2019 podflow!
    http://www.podflow.de
    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See license.txt for details.
**********************************************************************/

<?php
include('dbsettings.php');
$con = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD);
mysqli_select_db($con,DB_NAME);

if ($con->connect_error) {
    die('Connect Error (' . $con->connect_errno . ') '
            . $con->connect_error);
}
?>
