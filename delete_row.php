<?php
// Include your database connection here
require_once('config/db_config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Delete the row from the table where id matches
    $sql = "DELETE FROM food_log WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        // Return a success response
        echo json_encode(['success' => true]);
    } else {
        // Return an error response
        echo json_encode(['success' => false, 'message' => 'Error deleting row']);
    }
}
$conn->close();
?>
