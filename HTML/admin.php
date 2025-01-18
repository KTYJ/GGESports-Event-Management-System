<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
    <link rel="stylesheet" href="/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        td.button a{
            color: transparent;
        }
    </style>
</head>
<body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<?php
$PAGE_TITLE = 'Select Student';

include('additionalhelp.php');
include('sqlcon.php');
?>
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
            <a href="manage_booking.php">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                <span>Manage Booking</span>
            </a>
        </li>
        <li>
            <a href="userlist.php">
                <i class="fa fa-users" aria-hidden="true"></i>
                <span>User List</span>
            </a>
        </li>
    </ul>
</div>
<div class="content">
    <div class="wrapper">
            <strong>Welcome To GGE ADMIN PAGE</strong>
    </div>
    <div class="main-content">

    <?php
        $headers = array(
            'EventID'   => 'Event ID',
            'EventName' => 'Event Name',
            'Time'      => 'Time',
        );
        
        $sort  = empty($_GET) ? 'EventID' : (array_key_exists($_GET['sort'], $headers) ? $_GET['sort'] : 'EventID');
        $order = empty($_GET) ? 'ASC' : ($_GET['order'] == 'DESC' ? 'DESC' : 'ASC');

        $eventName = isset($_GET['eventName']) ? (array_key_exists($_GET['eventName'], $EVENTS) ? $_GET['eventName'] : '%') : '%';
    
    ?>
    <table class="content-table" border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td id="tablehead" colspan="5"><b>GGE's ADMIN CONTROL PANEL</b></td>
        </tr>
   
    <?php
    $count = 0;
    foreach ($headers as $key => $value)
    {
        if($count == 2){
            echo "<th>Image</th>";
        }
        if ($key == $sort) // The sorted field.
        {
            printf('
            <th>
            <a href="?sort=%s&order=%s&eventName=%s">%s</a>&nbsp;&nbsp;%s
            </th>',
            $key,
            $order == 'ASC' ? 'DESC' : 'ASC',
            $eventName,
            $value,
            $order == 'ASC' ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>'); // Alt text.
        }
        else // Non-sorted field.
        {
            printf('
                <th>
                <a href="?sort=%s&order=ASC&eventName=%s">%s</a>
                </th>',
                $key,
                $eventName,
                $value);
        }

        $count++;
    }       
    ?>

<th>Action</th>
    </tr>
    <?php
        $con = new mysqli('localhost', 'root', '' , 'gge');
        
        if($con->connect_error){
            die("Connection failed: " . $con->connect_error);
        }
        
        $sql = "SELECT * FROM event_form WHERE 'eventName' LIKE '".$eventName."' ORDER BY ".$sort." ".$order;
        
        $result = $con->query($sql);
        
        if ($result->num_rows > 0) // got record return
        {
            while ($row = $result->fetch_object())
            {
                printf('
                <tbody>
                    <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td class="img">
                        <a href="%s" target="_blank"><div class="noimg"><ion-icon name="image"></ion-icon></div></a>
                        </td>
                        <td>%s</td>
                        <td class="button">
                            <a href="edit.php?id=%s">
                                <button type="button" class="iconbuttonedit">
                                    <span class="button_textedit">Edit</span>
                                    <span class="button_iconedit"><ion-icon name="create-outline"></ion-icon></span>
                                </button>
                            </a>
                            <a href="delete.php?id=%s">
                                <button type="button" class="iconbuttondelete">
                                <span class="button_textdelete">Delete</span>
                                <span class="button_icondelete"><ion-icon name="trash-outline"></ion-icon></span>
                                </button>
                            </a>
                        </td>
                    </tr>
                 </tbody>',
                $row->EventID,
                $row->EventName,
                ((!empty($row->file))?("../Upload/".$row->file):"../Media/NA52bdfd8.jpg"),
                $row->Time,
                $row->EventID,
                $row->EventID
                );
            }
            
            printf('
            <tr>
                <td colspan="5">
                
                    %d Records Has Been Returned.&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="create.php">
                    <button type="button" class="iconbuttoncreate">
                    <span class="button_textcreate">Add New Event</span>
                    <span class="button_iconcreate"><ion-icon name="add-circle-outline"></ion-icon></span>
                    </button></a>
                </td>
            </tr>',
            $result->num_rows);
            
            $result->free();
            $con->close();
        }
        else // no record found
        {
    ?>
             <tr>
                 <td colspan="5">No Record Has Been Found&nbsp;&nbsp;&nbsp;&nbsp; 
                    <a href="create.php">
                    <button type="button" class="iconbuttoncreate">
                    <span class="button_textcreate">Add New Event</span>
                    <span class="button_iconcreate"><ion-icon name="add-circle-outline"></ion-icon></span>
                    </button></a>
                </td>
             </tr>
    <?php
        }
    ?>
     </table>
    

                </div>
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