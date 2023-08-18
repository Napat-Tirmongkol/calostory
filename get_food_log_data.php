<?php
session_start();
require_once('config/db_config.php');

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    
    $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
    $resultSelect = $conn->query($sqlSelect);
    
    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $id_user = $row["id_user"];
        
        // Get today's date
        $todayDate = date('Y-m-d');
        
        // Retrieve data from food_log table for today's date
        $sql = "SELECT fl.id, f.name, f.cal, f.water, fl.log_date, fl.log_time
                FROM food_log AS fl
                JOIN food AS f ON fl.idfood = f.idfood
                WHERE fl.id_user = '$id_user' AND fl.log_date = '$todayDate'";
        
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            $foodLogData = [];
            
            while ($row = $result->fetch_assoc()) {
                $foodLogData[] = $row;
            }
            
            header('Content-Type: application/json');
            echo json_encode($foodLogData);
        } else {
            echo "No matching records found.";
        }
    } else {
        echo "No matching user found.";
    }
    
    $conn->close();
}
?>
