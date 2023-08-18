<?php 
session_start();
$servername = "157.245.59.56";
$username = "aiclub";
$password = "AIclub4321;";
$dbname = "aiclub";
$port = "3366";

$conn = new mysqli($servername, $username, $password, $dbname, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>