<?php
session_start();
if (isset($_POST['userId'])) {
  $_SESSION['userId'] = $_POST['userId'];
}

?>
