<?php
session_start();
require_once('config/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['userId'])) {
        $userId = $_SESSION['userId'];
        
        // สร้างคำสั่ง SQL เพื่อดึงข้อมูล id_user
        $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
        $resultSelect = $conn->query($sqlSelect);
        
        if ($resultSelect->num_rows > 0) {
            $row = $resultSelect->fetch_assoc();
            $id_user = $row["id_user"];
            
            // ดึงค่า idfood จาก POST
            $idfood = $_POST['idfood'];

            // สร้างคำสั่ง SQL เพื่อบันทึกข้อมูลลงในตาราง food_log
            $sqlInsert = "INSERT INTO food_log (idfood, id_user, log_date, log_time) 
                          VALUES ('$idfood', '$id_user', CURDATE(), CURTIME())";

            // ทำการ execute คำสั่ง SQL เพื่อบันทึกข้อมูล
            if ($conn->query($sqlInsert) === TRUE) {
                echo "add food items succeed !!";
            } else {
                echo "Error: " . $sqlInsert . "<br>" . $conn->error;
            }
        } else {
            echo "No matching user found.";
        }
        
        $conn->close();
    }
}
?>
