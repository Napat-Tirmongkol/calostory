<?php
session_start();
require_once('config/db_config.php');

if (isset($_GET["keyword"])) {
    $keyword = $_GET["keyword"];
    $sql = "SELECT * FROM food WHERE name LIKE '%$keyword%'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table class='table'>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>ชื่ออาหาร</th>
                        <th>แคลอรี่ (cal)</th>
                        <th>ปริมาณน้ำ (mL)</th>
                    </tr>
                </thead>
                <tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['idfood']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['cal']}</td>
                    <td>{$row['water']}</td>
                    <td>
                        <a href='#' class='btn btn-success btn-circle' data-toggle='modal' data-target='#food-data' data-food-id='{$row['idfood']}'>
                            <i class='fa-solid fa-plus'></i> 
                        </a>
                    </td>
                  </tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No results found.</p>";
    }
}

$conn->close();
?>

<?php
include('includes/scripts.php');
include('includes/header.php');
?>

<div class="modal fade" id="food-data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2>เพิ่มข้อมูลอาหาร</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="table-responsive">
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label>รหัสอาหาร</label>
                            <input type="text" class="form-control" id="idfood" name="idfood" readonly value="123">
                        </div>
                        <div class="form-group">
                            <label>ชื่ออาหาร</label>
                            <input type="text" class="form-control" id="name" name="name" readonly
                                value="ชื่ออาหารที่ต้องการ">
                        </div>
                        <div class="form-group">
                            <label>แคลอรี่</label>
                            <input type="text" class="form-control" id="cal" name="cal" readonly value="200">
                        </div>
                        <div class="form-group">
                            <label>ปริมาณน้ำ (mL)</label>
                            <input type="text" class="form-control" id="water" name="water" readonly value="150">
                        </div>
                        <div class="form-group">
                            <button type="submit" id="food-data" class="addFoodLink">เพิ่มอาหาร</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>