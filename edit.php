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

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $sql = "SELECT * FROM napat63 WHERE userId = '$userId'";
    $result = $conn->query($sql);
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $conn->close();
}
?>
<div class="container-fluid d-flex justify-content-center">
    <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-center">
            <?php if (isset($profile->picture)) { ?>
                <img src="<?php echo $profile->picture; ?>" class="profile-img" alt="img user">
            <?php } else { ?>
                <img src="images/user.jpg" class="profile-img" alt="img user">
            <?php } ?>
            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                <?php echo $profile->name; ?>
            </span>
        </div>
        <div class="card-body">
            <form action="userupdate_db.php" method="POST" onsubmit="return validateForm();">
                <div class="form-group">
                    <label>Firstname</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo isset($name) ? $name : $row['name']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Lastname</label>
                    <input type="text" class="form-control" id="lastname" name="lastname"
                        value="<?php echo isset($lastname) ? $lastname : $row['lastname']; ?>" required>
                </div>
                <div class="form-group">
                    <label>weight</label>
                    <input type="number" class="form-control" id="weight" name="weight"
                        value="<?php echo isset($weight) ? $weight : $row['weight']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Height</label>
                    <input type="number" class="form-control" id="height" name="height"
                        value="<?php echo isset($height) ? $height : $row['height']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select class="form-control" id="gender" name="gender" required>
                        <option value="" disabled selected>Select gender</option>
                        <option value="male" <?php echo isset($gender) && $gender == 'male' ? 'selected' : ($row['gender'] == 'male' ? 'selected' : ''); ?>>Male</option>
                        <option value="female" <?php echo isset($gender) && $gender == 'female' ? 'selected' : ($row['gender'] == 'female' ? 'selected' : ''); ?>>Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>movementLevel</label>
                    <select class="form-control" id="movementLevel" name="movementLevel" required>
                        <option value="" disabled selected>Select movementLevel</option>
                        <option value="1.2" <?php echo isset($movementLevel) && $movementLevel == '1.2' ? 'selected' : ($row['movementLevel'] == '1.2' ? 'selected' : ''); ?>>ทำงานแบบนั่งอยู่กับที่</option>
                        <option value="1.375" <?php echo isset($movementLevel) && $movementLevel == '1.375' ? 'selected' : ($row['movementLevel'] == '1.375' ? 'selected' : ''); ?>>ออกกำลังกาย หรือเล่นกีฬา เเบบเบาๆ 1-3
                            วันต่อสัปดาห์</option>
                        <option value="1.55" <?php echo isset($movementLevel) && $movementLevel == '1.55' ? 'selected' : ($row['movementLevel'] == '1.55' ? 'selected' : ''); ?>>ออกกำลังกาย หรือเล่นกีฬา
                            ความหนักปานกลาง 3-5 วันต่อสัปดาห์</option>
                        <option value="1.725" <?php echo isset($movementLevel) && $movementLevel == '1.725' ? 'selected' : ($row['movementLevel'] == '1.725' ? 'selected' : ''); ?>>ออกกำลังกาย หรือเล่นกีฬา หนัก 6-7
                            วันต่อสัปดาห์</option>
                        <option value="1.9" <?php echo isset($movementLevel) && $movementLevel == '1.9' ? 'selected' : ($row['movementLevel'] == '1.9' ? 'selected' : ''); ?>>ออกกำลังกาย หรือเล่นกีฬา หนัก
                            แบบการซ้อมเพื่อแข่งขัน เป็นประจำทุกวัน</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="birthday">Birthday</label>
                    <input type="text" class="form-control datepicker" id="birthday" name="birthday"
                        value="<?php echo isset($birthday) ? $birthday : $row['birthday']; ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="javascript:void(0);" onclick="goBack();" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>