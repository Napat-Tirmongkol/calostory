<?php
session_start();
include('includes/header.php');
require_once('LineLogin.php');

if (isset($_SESSION['profile'])) {
  header("location:home.php");
}
?>

<body class="bg-gradient-primary">
<script src="https://static.line-scdn.net/liff/edge/versions/2.9.0/sdk.js"></script>
<script>
  function runApp() {
    liff.getProfile().then(profile => {
      document.getElementById("userId").innerHTML = '<b>UserId:</b> ' + profile.userId;
      document.getElementById("pictureUrl").src = profile.pictureUrl;
      document.getElementById("displayName").innerHTML = '<b>DisplayName:</b> ' + profile.displayName;
      document.getElementById("getDecodedIDToken").innerHTML = '<b>Email:</b> ' + liff.getDecodedIDToken().email;
      // ส่ง userId ไปยังเซิร์ฟเวอร์ PHP ผ่าน AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "save_user.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          console.log("User ID saved successfully!");
        }
      };
      xhr.send("userId=" + profile.userId);
    }).catch(err => console.error(err));
  }
  liff.init({ liffId: "2000054691-9VvG8kXY" }, () => {
    if (liff.isLoggedIn()) {
      runApp();
    } else {
      liff.login();
    }
  }, err => console.error(err.code, error.message));
</script>

  <div class="container">
    <!-- Page Heading -->
    <br>
    <br>
    <br>
    <br>
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">welcom to calostory</h1>
    </div>
    <br>
    <img src="https://cdn.discordapp.com/attachments/721330944304349186/1135159967817666590/logo_calostory.png"
      alt="img" class="img-fluid">
    <br>
    <br>
    <br>
    <br>
    <p id="userId" style="display: none;"></p>
    <!-- Content Row -->
    <div class="row">
      <!-- Earnings (Monthly) Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="h5 mb-0 d-flex justify-content-center ">
                  <?php
                  if (!isset($_SESSION['profile'])) {
                    $line = new LineLogin();
                    $link = $line->getLink();
                    ?>
                    <a href="<?php echo $link; ?>" class="btn btn-success me-2">Line Login</a>
                  <?php } else { ?>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                  <?php } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</body>