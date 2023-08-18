<?php
session_start();
require_once('LineLogin.php');
require_once('config/db_config.php');
$line = new LineLogin();
$get = $_GET;
$code = $get['code'];
$state = $get['state'];

$token = $line->token($code, $state);

if (property_exists($token, 'error')) {
    header('location:index.php');
    exit;
}

if ($token->id_token) {
    $profile = $line->profileFormIdToken($token);
    $_SESSION['profile'] = $profile;

    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];

        $sql = "SELECT userId FROM napat63 WHERE userId = '$userId'";
        $result = $conn->query($sql);

        // เช็คว่ามีข้อมูลในตารางหรือไม่
        if ($result->num_rows > 0) {
            // พบ userId ในตาราง napat63
            // ไปที่หน้า home.php
            header('Location: home.php');
            exit;
        } else {
            // ไม่พบ userId ในตาราง napat63
            // ไปที่หน้า form.php
            header('Location: form.php');
            exit;
        }
    } 
}

header('location:index.php');
exit;
?>