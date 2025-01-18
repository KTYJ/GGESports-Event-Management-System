<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="stylesheet" href="/css/userlist.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
             echo '"';
        ?>
         width="50vh" height="50vh">
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
            'userId'   => 'User ID',
            'Name'     => 'User Name',
            'phone'    => 'Phone Number',
            'email'    => 'Email'
           // 'file'     => 'Profile Image',
           //Usually admins won't be able to see your password since passwords are usually not stored in plain text, instead they will store only your password's hash.
        );
        
        $sort  = empty($_GET) ? 'userId' : (array_key_exists($_GET['sort'], $headers) ? $_GET['sort'] : 'EventID');
        $order = empty($_GET) ? 'ASC' : ($_GET['order'] == 'DESC' ? 'DESC' : 'ASC');

        $Name = isset($_GET['Name']) ? (array_key_exists($_GET['Name'], $EVENTS) ? $_GET['Name'] : '%') : '%';
    
    ?>
    <table class="content-table" border="1" cellpadding="5" cellspacing="0">
        <tr>
            <td id="tablehead" colspan="5"><b>GGE's USER LIST</b></td>
        </tr>
   
    <?php
    foreach ($headers as $key => $value)
    {
        if ($key == $sort) // The sorted field.
        {
            printf('
            <th>
            <a href="?sort=%s&order=%s&Name=%s">%s</a>&nbsp;&nbsp;%s
            </th>',
            $key,
            $order == 'ASC' ? 'DESC' : 'ASC',
            $Name,
            $value,
            $order == 'ASC' ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>'); // Alt text.
        }
        else // Non-sorted field.
        {
            printf('
                <th>
                <a href="?sort=%s&order=ASC&Name=%s">%s</a>
                </th>',
                $key,
                $Name,
                $value);
        }
    } 
    echo "<th>Profile Image</th>"     //Image does not need to be sorted.
    ?>
    
    </tr>
    <?php
        $con = new mysqli('localhost', 'root', '' , 'gge');
        
        if($con->connect_error){
            die("Connection failed: " . $con->connect_error);
        }
        
        $sql = "SELECT * FROM user WHERE 'Name' LIKE '".$Name."' ORDER BY ".$sort." ".$order;
        
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
                        <td>%s</td>
                        <td>%s</td>
                        <td>
                        <a href="%s" target="_blank"><div class="noimg"><ion-icon name="image"></ion-icon></div></a>
                        </td>
                    </tr>
                 </tbody>',
                $row->userId,
                $row->Name,
                $row->phone,
                $row->email,
                (!empty($row->file))?("../Upload/".$row->file):"../Media/profile.jpg"   
                );
            }
            
            printf('
            <tr>
                <td colspan="5">
                    %d Records Has Been Returned.
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
                 <td colspan="5">No Record Has Been Found.
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

<?php
    }else{
        header("Location: Homepage.php");
        exit();
    }
?>
</body>
</html>