<?php
require_once('config/db_config.php'); // Include your database connection

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];

    // Step 1: Retrieve id_user
    $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
    $resultSelect = $conn->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $id_user = $row["id_user"];

        // Step 2: Retrieve data from calories_log table for the specific id_user
        $sql = "SELECT log_date, cal FROM calories_log WHERE id_user = '$id_user'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo "No data found for the user.";
        }
    } else {
        echo "No matching user found.";
    }
} else {
    echo "User not logged in.";
}
?>
