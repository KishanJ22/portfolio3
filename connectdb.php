<?php
$dbname = 'u_220031626_db';
$dbhost = 'localhost';
$username = 'u-220031626';
$password = 'lbvR1trudwuFAF3';

try {
    $connection = mysqli_connect($dbhost, $username, $password, $dbname);
} catch(mysqli_sql_exception) {
    echo "Could not connect";
}
?>