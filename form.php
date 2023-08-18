<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');
require_once('LineLogin.php');

if (isset($_SESSION['profile'])) {
  $profile = $_SESSION['profile'];
}

if (!isset($_SESSION['profile'])) {
  header("location:index.php");
}
?>
<div class="card-body">
  <div class="d-flex justify-content-center">
    <!-- <h5 class="title" id="exampleModalLabel">Add Admin Data</h5> -->
    <img src="<?php echo $profile->picture; ?>" class="profile-img" alt="img user">
  </div>
  <h1 class="d-flex justify-content-center">
    <?php echo $profile->name; ?>
  </h1>
  <form class="p-3 mb-2" action="insertIn.php" method="POST" onsubmit="return validateForm();">

    <div class="form-group">
      <label>Firstname </label>
      <input type="text" name="name" class="form-control" placeholder="Enter Firstname">
    </div>
    <div class="form-group">
      <label>Lastname</label>
      <input type="text" name="lastname" class="form-control" placeholder="Enter Lastname">
    </div>
    <div class="form-group">
      <label>Weight</label>
      <input type="number" name="weight" class="form-control" placeholder="Enter weight">
    </div>
    <div class="form-group">
      <label>Height</label>
      <input type="number" name="height" class="form-control" placeholder="Enter height">
    </div>

    <div class="form-group">
      <label>birthday</label>
      <input type="text" class="form-control datepicker" id="birthday" name="birthday" placeholder="Birthday">
    </div>

    <div class="form-group">
      <label>gender</label>
      <br>
      <select id="gender" name="gender">
        <option value="" disabled selected>Gender</option>
        <option value="male">male</option>
        <option value="female">female</option>
      </select>
    </div>

    <div class="form-group">
      <label>Movement Level</label>
      <br>
      <select class="input-field" id="movementLevel" name="movementLevel">
        <option disabled selected>Movement Level</option>
        <option value="1.2">ทำงานแบบนั่งอยู่กับที่</option>
        <option value="1.375">ออกกำลังกาย หรือเล่นกีฬา เเบบเบาๆ 1-3 วันต่อสัปดาห์</option>
        <option value="1.55">ออกกำลังกาย หรือเล่นกีฬา ความหนักปานกลาง 3-5 วันต่อสัปดาห์</option>
        <option value="1.725">ออกกำลังกาย หรือเล่นกีฬา หนัก 6-7 วันต่อสัปดาห์</option>
        <option value="1.9">ออกกำลังกาย หรือเล่นกีฬา หนัก แบบการซ้อมเพื่อแข่งขัน เป็นประจำทุกวัน</option>
      </select>
    </div>
    <div class="form-check">
      <input class="form-check-input" type="checkbox" value="-500" id="lose-weight">
      <label class="form-check-label" for="flexCheckIndeterminate">
        ต้องการลดน้ำหนัก
      </label>
    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">submit</button>
    </div>
  </form>
</div>

<!-- /.container-fluid -->

<?php
include('includes/scripts.php');
include('includes/footer.php');
?>