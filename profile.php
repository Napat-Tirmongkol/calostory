<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
require_once('config/db_config.php');
require_once('LineLogin.php');

if (isset($_SESSION['profile'])) {
    $profile = $_SESSION['profile'];
}

if (!isset($_SESSION['profile'])) {
    header("location:index.php");
}

$sql = "SELECT weight, height, bmi, tdee, water, age, bmr FROM napat63";

// เพิ่มเงื่อนไข WHERE หากมีค่า userId ใน Session
if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $sql .= " WHERE userId = '$userId'";
}

$result = $conn->query($sql);

// ตรวจสอบผลลัพธ์จากการดึงข้อมูล
if ($result !== false && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $weight = $row['weight'];
    $height = $row['height'];
    $tdee = $row['tdee'];
    $bmi = $row['bmi'];
    $water = $row['water'];
    $age = $row['age'];
    $bmr = $row['bmr'];

}
mysqli_close($connection);

?>

<div class="container-fluid d-flex justify-content-center">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-center">
            <?php if (isset($profile->picture)) { ?>
                <img src="<?php echo $profile->picture; ?>" class="profile-img" alt="img user">
            <?php } else { ?>
                <img src="images/user.jpg" class="profile-img" alt="img user">
            <?php } ?>
            <h4 class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?php echo $profile->name; ?>
            </h4>
        </div>
        <div class="row">

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    <?php echo $weight; ?> kg
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    <?php echo $height; ?> cm
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    <?php echo $age; ?> years old
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    water
                                    <?php echo $water * 1000; ?> ml
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    bmi
                                    <?php echo $bmi; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    bmr
                                    <?php echo $bmr; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="col mr-2">
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                <span>
                                    tdee
                                    <?php echo $tdee; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
include('includes/scripts.php');
include('includes/footer.php');
?>