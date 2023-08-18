<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
require_once('config/db_config.php');
require_once('LineLogin.php');

$currentDate = date("Y-m-d");
$currentTime = date("H:i:s");

if (isset($_SESSION['profile'])) {
  $profile = $_SESSION['profile'];
}

if (!isset($_SESSION['profile'])) {
  header("location:index.php");
}

$sql = "SELECT weight, height, bmi, tdee, water FROM napat63";

if (isset($_SESSION['userId'])) {
  $userId = $_SESSION['userId'];
  $sql .= " WHERE userId = '$userId'";
}

$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์จากการดึงข้อมูล
if ($result !== false && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $tdee = $row['tdee'];
  $bmi = $row['bmi'];
  $water = $row['water'];
}

if (isset($_GET["search_term"])) {
  $keyword = $_GET["search_term"];
  $sql = "SELECT * FROM food WHERE name LIKE '%$keyword%'";
  $result = $conn->query($sql);
}
if (isset($_SESSION['userId'])) {
  $userId = $_SESSION['userId'];

  // Get today's date
  $todayDate = date('Y-m-d');

  // Step 1: Retrieve id_user
  $sqlSelect = "SELECT id_user FROM napat63 WHERE userId = '$userId'";
  $resultSelect = $conn->query($sqlSelect);

  if ($resultSelect->num_rows > 0) {
    $row = $resultSelect->fetch_assoc();
    $id_user = $row["id_user"];

    // Step 2: Retrieve related idfood entries from food_log for today's date
    $sqlFoodLog = "SELECT idfood FROM food_log WHERE id_user = '$id_user' AND log_date = '$todayDate'";
    $resultFoodLog = $conn->query($sqlFoodLog);

    $totalCalories = 0;
    $totalWater = 0;

    if ($resultFoodLog->num_rows > 0) {
      while ($foodLogRow = $resultFoodLog->fetch_assoc()) {
        $idfood = $foodLogRow['idfood'];

        // Step 3: Retrieve cal and water values from food table
        $sqlFood = "SELECT cal, water FROM food WHERE idfood = '$idfood'";
        $resultFood = $conn->query($sqlFood);

        if ($resultFood->num_rows > 0) {
          $foodRow = $resultFood->fetch_assoc();
          $totalCalories += $foodRow['cal'];
          $totalWater += $foodRow['water'];
        }
      }
    }

    // Check if the entry for today already exists in calories_log table
    $sqlCheckExistence = "SELECT * FROM calories_log WHERE id_user = '$id_user' AND log_date = '$todayDate'";
    $resultCheckExistence = $conn->query($sqlCheckExistence);

    if ($resultCheckExistence->num_rows > 0) {
      // Update the existing entry
      $sqlUpdate = "UPDATE calories_log SET cal = '$totalCalories', water = '$totalWater'
                  WHERE id_user = '$id_user' AND log_date = '$todayDate'";
      $conn->query($sqlUpdate);
    } else {
      // Insert a new entry
      $sqlInsert = "INSERT INTO calories_log (id_user, cal, water, log_date)
                  VALUES ('$id_user', '$totalCalories', '$totalWater', '$todayDate')";
      $conn->query($sqlInsert);
    }

  }
}

$conn->close();
?>

<div class="modal fade" id="addfood" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2>
          food items
        </h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <!-- <form action="#" method="GET"> -->
      <div class="modal-body">
        <form method="GET" action="">
          <div class="input-group mb-3">
            <input type="text" id="search_term" name="search_term" class="form-control" placeholder="ค้นหาข้อมูล...">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" id="searchBtn">ค้นหา</button>
            </div>
          </div>
        </form>
        <div id="foodLogTable"></div> <!-- ตาราง food_log จะแสดงที่นี่ -->
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="registerbtn" class="btn btn-primary">Save</button> -->
      </div>
    </div>
  </div>
</div>


<div class="container-fluid d-flex justify-content-center">
  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-center">

      <div class="col-md-4">
        <div class="card p-3 mb-2">
          <div class="d-flex justify-content-between">
            <div class="d-flex flex-row align-items-center">
              <!-- <div class="icon"> <i class="bx bxl-mailchimp"></i> </div> -->
              <div class="ms-2 c-details">
                <h6 class="mb-0">TDEE</h6>
                <br>
                <i class="fa-solid fa-fire" style="color: #e05b2e; font-size: 80px;"></i>
              </div>
            </div>
          </div>
          <div class="mt-3 d-flex justify-content-around">
            <h6 class="heading">Target<br>
              <?php echo $tdee; ?>
            </h6>
            <h6 class="heading">remain<br>
              <?php echo $totalCalories - $tdee; ?>
            </h6>
            <!-- <h5 class="heading">Remain<br>2170</h5> -->
          </div>
          <div class="mt-3">
            <div class="progress">
              <div class="progress-bar" role="progressbar" style="width: <?php echo ($totalCalories / $tdee) * 100; ?>%"
                aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="mt-3"> <span class="text1">
                <?php echo $totalCalories; ?> of<span class="text2">
                  <?php echo $tdee; ?>
                </span>
              </span> </div>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card p-3 mb-2">
          <div class="d-flex justify-content-between">
            <div class="d-flex flex-row align-items-center">
              <!-- <div class="icon"> <i class="bx bxl-mailchimp"></i> </div> -->
              <div class="ms-2 c-details">
                <h6 class="mb-0">WATER</h6>
                <br>
                <i class="fa-solid fa-glass-water-droplet" style="color: #279EFF; font-size: 80px;"></i>
              </div>
            </div>
          </div>
          <div class="mt-3 d-flex justify-content-around">
            <h6 class="heading">Target<br>
              <?php echo $water * 1000; ?>mL
            </h6>
            <h6 class="heading">remain<br>
              <?php echo $totalWater - ($water * 1000); ?>mL
            </h6>
            <!-- <h5 class="heading">Remain<br>2170</h5> -->
          </div>
          <div class="mt-3">
            <div class="progress">
              <div class="progress-bar" role="progressbar"
                style="width: <?php echo ($totalWater / ($water * 1000)) * 100; ?>%" aria-valuenow="50"
                aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="mt-3"> <span class="text1">
                <?php echo $totalWater; ?> mL of<span class="text2">
                  <?php echo $water * 1000; ?> mL
                </span>
              </span> </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row d-flex justify-content-center p-3 mb-2">

      <div class="col-x2-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">BMI : Body Mass Index</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  <?php echo '<h5>' . $bmi . '</h5>'; ?>
                </div>
              </div>
              <div class="col-auto">
                <i class="fa-solid fa-child fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-x2-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">TDEE : Total Daily Energy
                  Expenditure</div>
                <div class="h6 mb-0 font-weight-bold text-gray-800">
                  <?php echo '<h5>' . ($tdee) . ' cl</h5>'; ?>
                </div>
              </div>
              <div class="col-auto">
                <i class="fa-solid fa-fire fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- หน้าที่แสดงตารางรายการอาหาร -->
    <div class="card-body">
      <h5>
        <?php
        $currentDateTime = date("d-m-Y");
        echo $currentDateTime;
        ?>
      </h5>

      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addfood">
        Add food
      </button>

      <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addmenu">
        Add Menu
      </button>

      <div class="modal fade" id="addmenu" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form id="addMenuForm" method="post">
              <div class="modal-header">
                <h2>Add Menu</h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
                </div>
                <div class="form-group">
                  <label>Calorie</label>
                  <input type="number" name="cal" class="form-control" placeholder="Enter Calorie" step="1" required>
                </div>
                <div class="form-group">
                  <label>Water/ml</label>
                  <input type="number" name="water" class="form-control" placeholder="ml" step="1" required>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>


      <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#Charts">
        Open Chart
      </button>

      <!-- <div id="calendar"></div> -->

      <div class="modal fade" id="Charts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h2>Calories Chart</h2>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <canvas id="caloriesChart"></canvas>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="table-responsive">
      <table class="table table-bordered" id="foodLogTable_user" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>food</th>
            <th>calorie</th>
            <th>water/mL</th>
            <th>DELETE </th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  
  </div>

  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Food Log</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- เพิ่มฟอร์มแก้ไขข้อมูลตรงนี้ -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

</div>

</div>
<!-- /.container-fluid -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>