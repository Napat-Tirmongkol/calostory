<?php
// Include your database configuration
require_once('config/db_config.php');

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
  
    $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
    $resultSelect = $conn->query($sqlSelect);
  
    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $id_user = $row["id_user"];

        $sql = "SELECT log_date, cal FROM calories_log WHERE id_user = '$userId'";
        $result = $conn->query($sql);

        $events = array();

        while ($row = $result->fetch_assoc()) {
            $event = array(
                'title' => 'Food',
                'start' => $row['log_date'], // Food log date
                'backgroundColor' => '#FF5733' // Customize event color
            );
            $events[] = $event;
        }

        header('Content-Type: application/json');
        echo json_encode($events);
    }
}

$conn->close();
?>
