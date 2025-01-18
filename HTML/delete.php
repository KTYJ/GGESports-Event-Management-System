<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>
<?php
$PAGE_TITLE = 'Delete Event';
include('additionalhelp.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE-edge" />
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Delete Event</title>
   <link rel="stylesheet" href="/css/delete.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

    </style>
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
        if ($_SERVER['REQUEST_METHOD'] == 'GET')
        {
            
            $con = new mysqli('localhost', 'root', '', 'gge');
            $id = strtoupper(trim($_GET['id']));
            
            $id  = $con->real_escape_string($id);
            $sql = "SELECT * FROM event_form WHERE EventID = '".$id."'";
            
            $result = $con->query($sql);
            
            if ($row = $result->fetch_object())
            {
                $id       = $row->EventID;
                $name     = $row->EventName;
                $time     = $row->Time;
                
                printf('<div class="text1">
                <table cellpadding="5" cellspacing="0">
                <tbody>
                    <tr>
                        <td id="tablehead" colspan="2"><b>GGE CONTROL PANEL</b></td>
                    </tr>
                    <tr>
                        <td>Event ID :</td>
                        <td>%s</td>
                    </tr>
                    <tr>
                        <td>Event Name :</td>
                        <td>%s</td>
                    </tr>
                    <tr>
                        <td>Time :</td>
                        <td>%s</td>
                    </tr>
                    <tr>
                        <td id="mention" colspan="2"><p>Are You Sure You Want To <b>Delete</b> The Following Record?</p></td>
                    <tr>
                    </tbody>
                </table>
                <form action="" method="post">
                </br>
                    <input type="hidden" name="id" value="%s" />
                    <input type="hidden" name="name" value="%s" />
                    <div class="click">
                    <button type="submit" class="button_submit">
                    <span class="button_textsubmit">Sure</span>
                    <span class="button_iconsubmit"><ion-icon name="trash-outline"></ion-icon></span></button>
                    
                    <a href="admin.php">
                    <button type="button" class="button_cancel">
                    <span class="button_textcancel">Cancel</span>
                    <span class="button_iconcancel"><ion-icon name="arrow-back-circle-outline"></ion-icon></span>
                    </button></a>
                    </div>
                </form>
                </div>',
                $id, $name, $time,
                $id, $name);
            }
            else
            {
                echo '
                <div class="error">
                Opps. Record not found.
                <input type="button" value="Back To List" class="btn btn-secondary btn-sm" onclick="location=\'admin.php\'"/>
                </div>
                ';
            }
            
            $result->free();
            $con->close();
        }
        else
        {
            // process deletion
            $id   = strtoupper(trim($_POST['id']));
            $name = trim($_POST['name']);
            
            $con = new mysqli('localhost', 'root', '', 'gge');
            
            $sql = '
            DELETE FROM event_form
            WHERE EventID = ?
            ';
            
            $stm = $con->prepare($sql);
            $stm->bind_param('s', $id);
            $stm->execute();
            
            if ($stm->affected_rows > 0)
            {
                printf('
                <div class="info">
                <img src="/Media/tick.jpg">
                <h2>Event <strong>%s</strong> has been deleted.</h2>
                <input type="button" value="OK"class="info_button"onclick="location=\'admin.php\'"/></div>',
                $id);
            }
            else // deletion failed
            {
                echo '
                <div id ="error">
                Opps. Database issue. Record not deleted.
                </div>
                ';
            }
            
            $stm->close();
            $con->close();
        }
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