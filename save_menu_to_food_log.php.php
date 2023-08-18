<?php
session_start();
require_once('config/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['name'], $_POST['cal'], $_POST['water'])) {
    $name = $_POST['name'];
    $cal = $_POST['cal'];
    $water = $_POST['water'];
    if (isset($_SESSION['userId'])) {
      $userId = $_SESSION['userId'];
      // Get the id_user from the napat63 table based on userId
      $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
      $resultSelect = $conn->query($sqlSelect);

      if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $id_user = $row["id_user"];

        // Insert data into the food_log table
        $sqlInsert = "INSERT INTO food (id_user, name, cal, water)
                          VALUES ('$id_user', '$name', '$cal', '$water')";

        if ($conn->query($sqlInsert) === TRUE) {
          echo json_encode(['success' => true]);
        } else {
          echo json_encode(['success' => false, 'message' => 'Error inserting data']);
        }
      } else {
        echo json_encode(['success' => false, 'message' => 'No matching user found']);
      }
    } else {
      echo json_encode(['success' => false, 'message' => 'Missing data']);
    }

    $conn->close();
  }
}
?>