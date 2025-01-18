<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Edit Panel</title>
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/approve_booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  </head>
<body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<div class="sidebar">
<ul class="menu">
    <div class="logo">
        <img src="/media/GGE.jpg" width="90%"/>
        <span id="admintitle">Admin</span>
    </div>
    <div align="center">
    <br/>
        <div class="date">
            <span id="clock" class="time"></span>
            <br/>
            <span id="date1" class="time"></span>
        </div>
        <br/>
        <?php
            echo '<img src="../';
             $adminid = $_SESSION['admin_id'];
             $con = new mysqli('localhost','root', '', 'gge');
             $sql = "SELECT * FROM admin WHERE adminID='$adminid'";
             $result = mysqli_query($con,$sql);
             if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);
                if(!empty($row['file'])){
                   echo "Upload/".$row['file'];
                }
             }
             else{
                 echo 'Media/profile.jpg'; //no profile picture selected
             }
             echo '"width="50vh" height="50vh">';
        ?>
        <br/>
                
        SIGNED IN AS:
        <span id="aName">
        <?php
            echo $_SESSION['admin_id'];
        ?>
        </span> <!--Name-->  
        <br/><br/>
        <img src="../Media/logout.jpg" id="logout" width="20px" onclick="logOut()"/>
        <script>
            function logOut(){
            if (confirm("Are you sure want to logout?")){
                window.location.href = "logout.php";
            }
            }
        </script>
    </div>
        <li>
            <a href="adminh.php">
                <i class="fa fa-home" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </li>
        <li>
            <a href="admin.php">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <span>Manage Events</span>
            </a>
        </li>
        <li>

            <a href="editprofile.php<?php
                if(isset($_SESSION['admin_id'])){
                    echo "?adminId=".$_SESSION['admin_id'];
                }
            ?>">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Edit Profile</span>
            </a>
        </li>
        <li>
            <a href="events_list.php">
                <i class="fa fa-list" aria-hidden="true"></i>
                <span>Events List</span>
            </a>
        </li>
        <li>
            <a href="manage_booking.php">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                <span>Manage Booking</span>
            </a>
        </li>
    </ul>
</div>  
<div class="content">
    <div class="wrapper">
            <strong>Edit Event Page</strong>
    </div>
    <div class="main-content">

<?php
$PAGE_TITLE = 'Edit Event';
include('additionalhelp.php');
    
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $booking_id = isset($_GET['id']) ? trim($_GET['id']) : null;

        // Database connection
        $con = new mysqli('localhost', 'root', '', 'gge');
        $sqli = "SELECT * FROM booking WHERE booking_id = '$booking_id'";
        $result = mysqli_query($con,$sqli);
        if(mysqli_num_rows($result) != 0){
            // Prepare and execute UPDATE query
            $sql = "UPDATE booking SET bookStatus = 'approved' WHERE booking_id = ?";
            $stm = $con->prepare($sql);
            $stm->bind_param('s', $booking_id);
            $stm->execute();

            // Check if the query was successful
            if ($stm->affected_rows > 0) {
                printf('
                    <div class="info">
                    <img src="/Media/tick.jpg">
                    <h2>Event <strong>%s</strong> has been approved.</h2>
                    <input type="button" value="OK" class="info_button" onclick="location=\'manage_booking.php\'"/></div>',
                    $booking_id);
            }
            else {
                printf('
                <div class="info">
                <img src="/Media/tick.jpg">
                <h2>Event <strong>%s</strong> already been approved.</h2>
                <input type="button" value="OK" class="info_button" onclick="location=\'manage_booking.php\'"/></div>',
                $booking_id);
            }
        }else{
            echo '
            <div id="error">
            Oops, event not found.
            <p><a href="manage_booking.php">Back to page</a></p>
            </div>
            ';
        }
        // Close database connection
        }
        $stm->close();
        $con->close();
    ?>
    
</div>
</div>
<script type="text/javascript">
    window.onload = startTime();
    function startTime() {
        const weekArr = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];
        const monthArr = ["January","February","March","April","May","June","July","August","September","October","November","December"];

        const today = new Date();
        let h = today.getHours();
        let m = today.getMinutes();
        let s = today.getSeconds();

        let day = today.getDate();
        var week = weekArr[today.getDay()];
        var month = monthArr[today.getMonth()];

        document.getElementById("date1").innerHTML = week + ", " + day +" " + month ;
        h = checkTime(h);
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML =  h + ":" + m + ":" + s;
        setTimeout(startTime, 1000);
    }
    function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
    }
    
</script>
</body>
</html>
<?php
    }else{
        header("Location: Homepage.php");
        exit();
    }
?>