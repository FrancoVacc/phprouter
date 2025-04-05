<?php
$host = 'localhost';
$dbname = 'control';
$user = 'root';
$password = '';

$con = new mysqli($host, $user, $password, $dbname, '3307');

if ($con->connect_error) {
    die('ha fallado la conexión' . $con->connect_error);
}
