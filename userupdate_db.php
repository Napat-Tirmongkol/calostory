<?php
session_start();
require_once('LineLogin.php');
require_once('config/db_config.php');

if (isset($_SESSION['profile'])) {
    $profile = $_SESSION['profile'];
}

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
}


date_default_timezone_set("Asia/Bangkok");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $weight = $_POST['weight'];
    $height = $_POST['height'];
    $gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $movementLevel = $_POST['movementLevel'];
    $email = $_SESSION['profile']->email;
    $userId = $_SESSION['userId'];
    $bmi = $weight / $height / $height * 10000;
    $currentDate = date("Y-m-d");
    $age = date("Y") - date("Y", strtotime($birthday));
    // $loseWeight = isset($_POST['loseWeight']) ? $_POST['loseWeight'] : 0;
    
    if (date("m-d", strtotime($currentDate)) < date("m-d", strtotime($birthday))) {
        $age--;
    }

    // Calculate BMR 
    if ($gender === "male") {
        $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
    } elseif ($gender === "female") {
        $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
    } else {
        $bmr = 0;
    }

    $tdee = ($bmr  * $movementLevel);
    $water = $weight * 2.2 * 30 / 2 / 1000;

    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        $updateSql = "UPDATE napat63 SET weight = '$weight', height = '$height', gender = '$gender', birthday = '$birthday', movementLevel = '$movementLevel', bmi = '$bmi', bmr = '$bmr', tdee = '$tdee', water = '$water', age = '$age' WHERE userId = '$userId'";
    
        if ($conn->query($updateSql) === TRUE) {
            header("Location: home.php");
            exit;
        } else {
            echo "Error: " . $updateSql . "<br>" . mysqli_error($conn);
        }
    } else {
        echo "Error: User ID not set.";
    }
    
    $conn->close();
}    
?>