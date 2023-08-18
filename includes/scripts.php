<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js/dist/Chart.min.js"></script>

<!-- CSS -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.0/themes/base/jquery-ui.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.0/jquery-ui.min.js"></script>

<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: "yy/mm/dd",
            changeMonth: true,
            changeYear: true,
            yearRange: "1950:2005"
        });
    });
</script>
<script>
    var previousPageURL = document.referrer;
</script>
<script>
    function goBack() {
        if (previousPageURL) {
            window.location.href = previousPageURL;
        } else {
            window.location.href = "index.php";
        }
    }
</script>
<script>
    function validateForm() {
        var weight = document.getElementById('weight').value;
        var height = document.getElementById('height').value;
        var gender = document.getElementById('gender').value;
        var birthday = document.getElementById('birthday').value;
        var movementLevel = document.getElementById('movementLevel').value;
        if (weight === '' || height === '' || gender === '' || birthday === '' || movementLevel === '') {
            alert('Please fill in all fields.');
            return false;
        }
    }
</script>

<script>
    $(document).ready(function () {
        // ตรวจจับเหตุการณ์เมื่อคลิกที่ปุ่ม "ค้นหา"
        $("#searchBtn").on("click", function () {
            var keyword = $("#search_term").val();

            // ส่งคำขอ GET ไปยังหน้า PHP ที่จะดึงข้อมูลจากฐานข้อมูล
            $.ajax({
                url: "search_food.php",
                type: "GET",
                data: { keyword: keyword },
                success: function (response) {
                    // แสดงข้อมูลที่ได้รับจาก PHP ในตาราง food_log
                    $("#foodLogTable").html(response);
                },
                error: function () {
                    alert("เกิดข้อผิดพลาดในการโหลดข้อมูล");
                }
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        $('.btn-circle').click(function () {
            var idfood = $(this).closest('tr').find('td:eq(0)').text();
            var foodName = $(this).closest('tr').find('td:eq(1)').text();
            var calories = $(this).closest('tr').find('td:eq(2)').text();
            var water = $(this).closest('tr').find('td:eq(3)').text();

            $('#idfood').val(idfood); // ตรงนี้คือ id ของอาหาร
            $('#name').val(foodName);
            $('#cal').val(calories);
            $('#water').val(water);

            $('#food-data').modal('show'); // แสดง Modal เมื่อคลิก
        });
    });
</script>



<script>
    $(document).ready(function () {
        $('.addFoodLink').click(function () {
            var foodId = $('#idfood').val();

            $.ajax({
                url: "add_food_log.php",
                type: "POST",
                data: {
                    idfood: foodId,
                },
                success: function (response) {
                    alert(response);
                },
                error: function (xhr, status, error) {
                    var errorMessage = xhr.status + ": " + xhr.statusText;
                    alert("เกิดข้อผิดพลาดในการเพิ่มอาหาร: " + errorMessage);
                }
            });
        });
    });
</script>

<script>
    // ดึงข้อมูลจากเซิร์ฟเวอร์โดยใช้ AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'get_food_log_data.php', true); // เปลี่ยนเป็น URL ที่ใช้ดึงข้อมูล
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var responseData = JSON.parse(xhr.responseText);
            populateFoodLogTable(responseData);
        }
    };
    xhr.send();
    // แสดงข้อมูลในตาราง foodLogTable
    function populateFoodLogTable(data) {
        var tableBody = document.querySelector('#foodLogTable_user tbody');
        tableBody.innerHTML = ''; // Clear the previous data

        for (var i = 0; i < data.length; i++) {
            var row = data[i];
            var newRow = tableBody.insertRow();

            var cellFood = newRow.insertCell(0);
            var cellCal = newRow.insertCell(1);
            var cellWater = newRow.insertCell(2);
            var cellDelete = newRow.insertCell(3);

            cellFood.innerHTML = row.name;
            cellCal.innerHTML = row.cal;
            cellWater.innerHTML = row.water;
            cellDelete.innerHTML = `<button class="btn btn-danger" onclick="deleteRow(${row.id})">DELETE</button>`;
        }
    }


    // Function to handle the delete action
    function deleteRow(id) {
        var confirmed = window.confirm("Are you sure you want to delete?");
        if (confirmed) {
            fetch(`delete_row.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Deletion successful, update the table
                        updateTable();
                    } else {
                        // Handle error
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    }


    function updateTable() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'get_food_log_data.php', true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var responseData = JSON.parse(xhr.responseText);
                populateFoodLogTable(responseData);
            }
        };
        xhr.send();
    }


</script>

<script>
    $(document).ready(function () {
        document.getElementById('addMenuForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            var formData = new FormData(this);

            fetch('save_menu_to_food_log.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Data saved successfully, update the table
                        updateTable();
                        // Show success message
                        alert('Data saved successfully');
                        // Close the modal
                        ('#addmenu').modal('hide');
                    } else {
                        // Handle error
                        console.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
</script>

<script>
       fetch('fetch_calories_data.php')
           .then(response => response.json())
           .then(data => {
               var xValues = data.map(entry => entry.log_date);
               var yValues = data.map(entry => entry.cal);

               new Chart("caloriesChart", {
                   type: "line",
                   data: {
                       labels: xValues,
                       datasets: [{
                           data: yValues,
                           label: 'Calories',
                           borderColor: 'blue',
                           fill: false
                       }]
                   },
                   options: {
                       legend: {
                           display: true,
                           position: 'top'
                       },
                       title: {
                           display: true,
                           text: "Calories Log"
                       },
                       scales: {
                           x: {
                               type: 'time',
                               time: {
                                   unit: 'day',
                                   displayFormats: {
                                       day: 'MMM DD'
                                   }
                               }
                           },
                           y: {
                               beginAtZero: true,
                               title: {
                                   display: true,
                                   text: 'Calories'
                               }
                           }
                       }
                   }
               });
           })
           .catch(error => {
               console.error('Error:', error);
           });
   </script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Initial view of the calendar
        events: {
            url: 'get_food_data.php', // URL to fetch food data
            method: 'GET'
        }
    });
    
    calendar.render();
});
</script>
