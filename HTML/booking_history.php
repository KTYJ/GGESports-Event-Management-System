<?php
session_start();
@include 'sqlcon.php';

$stat = array(
    'approved' => 'Approved',
    'rejected' => 'Rejected',
    'pending'  => 'Pending'
);

function eventTime($eventid){
    ////You pas event into this function,
    //  e.g. printf('...', $eventid, --> eventTime($eventid)<-- );


    $con = new mysqli('localhost', 'root', '' , 'gge');
    $sql = "SELECT Time FROM event_form WHERE EventID='$eventid' ";

    $dateres = mysqli_query($con,$sql);
    if(mysqli_num_rows($dateres) === 1){
       $row = mysqli_fetch_assoc($dateres);
       if(!empty($row['Time'])){
            return $row['Time'];
       }
       else{
            return "Unknown";  //IF EVENT DELETED 
       }
    }

}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit;
}

// Retrieve logged-in user's ID
$user_id = $_SESSION['user_id'];

// Fetch booking history for the logged-in user
$sql = "SELECT * FROM booking WHERE member_id = '$user_id' ORDER BY booking_id DESC";

// Execute the query
$con = new mysqli('localhost', 'root', '', 'gge');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$result = $con->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="../CSS/Edit_Profile.css">
    <link rel="stylesheet" href="../CSS/Edit_Profile.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="/css/booking_history.css">
    <script src="../JAVAs/jquery-1.9.1.js">//Modify the <script> element to link to the local jQuery library file.</script>
    <script>
    $(document).ready(function() {
    $('td span').each(function() {
        var content = $(this).text().trim();
        if (content === 'Pending') {
            $(this).css('color', '#FFA500');
        } else if (content === 'Approved') {
            $(this).css('color', 'green');
        } else if (content === 'Rejected') {
            $(this).css('color', 'red');
            $(this).css('font-weight', 'bold');
            $(this).css('text-transform', 'uppercase');
        }
    });
});
</script>
</head>
<body>
<div class="sidebar">
<ul class="menu">
<div align="center">
<img src="/media/MainLogoNoBg.jpeg" width="90%"/>
    <br/>
    <span id="usertitle">User Page</span>
        <div class="date">
            <span id="clock" class="time"></span>
            <br/>
            <span id="date1" class="time"></span>
        </div>
        <br/>
        <br/>
        <?php
        echo '<img src="../';
             $userid = $_SESSION['user_id'];
             $sql = "SELECT * FROM user WHERE userId ='$userid'";
             $imgres = mysqli_query($con,$sql);
             if(mysqli_num_rows($imgres) === 1){
                $row = mysqli_fetch_assoc($imgres);
                if(!empty($row['file'])){
                   echo "Upload/".$row['file'];
                }
                else{
                    echo 'Media/profile.jpg'; //no profile picture selected
             }
            
             }
             echo '"width="50vh" height="50vh">';
             ?>                <br/>
                SIGNED IN AS:
                <span id="aName">
                <?php
                        echo $_SESSION['user_name'].
                    "<br/>";
                    echo "ID:".$_SESSION['user_id'];
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
            <a href="user.php">
                <i class="fa fa-home" aria-hidden="true"></i>
                <span>Home</span>
            </a>
        </li>
        <li>
            <a href="booking_history.php">
                <i class="fa fa-calendar" aria-hidden="true"></i>
                <span>Booking Status</span>
            </a>
        </li>
        <li>
            <a href="edit_User.php<?php
                if(isset($_SESSION['admin_id'])){
                    echo "?adminId=".$_SESSION['admin_id'];
                }
            ?>">
                <i class="fa fa-user" aria-hidden="true"></i>
                <span>Edit Profile</span>
            </a>
        </li>
    </ul>
</div>
        <div class="content">
    <h1>Booking History</h1>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Booking ID</th><th>Event ID</th><th>Event Date</th><th>Seats</th><th>Book Status</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr class='tbod'>";
            echo "<td >" . $row['booking_id'] . "</td>";
            echo "<td >" . $row['event_id'] . "</td>";
            echo "<td >" . eventTime($row['event_id']) . "</td>";
            echo "<td >" . $row['seats_selected'] . "</td>";
            echo "<td><span class='status'>" . $stat[$row['bookStatus']] . "</td>";
            printf('<td ><a href="user_delete_booking.php?id=%s">
            <button type="button" class="iconbuttondelete">
            <span class="button_textdelete">Delete</span>
            <span class="button_icondelete"><ion-icon name="trash-outline"></ion-icon></span>
            </button></a></td>', $row['booking_id'] );
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No booking history found.";
    }
    $con->close();
    ?>
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
        </div>
</body>
</html>