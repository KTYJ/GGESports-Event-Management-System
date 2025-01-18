<?php
   session_start();
   include('sqlcon.php');

   if (!isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit;
}
?>

<?php
    if(isset($_SESSION['user_id'])){
        /////BUG 
        $id = $_SESSION['user_id'];
        $sql = "SELECT * FROM user WHERE userId = '$id' ";
        $result = mysqli_query($con,$sql);
        if(mysqli_num_rows($result) > 0){
           $row = mysqli_fetch_assoc($result);
           $id = $row['userId'];
           $name = $row['Name'];
           $email = $row['email'];
           $phone = $row['phone'];
   
        }
    }


?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>USER PROFILE</title>
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../CSS/UserProfile.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  </head>
<body>
<div class="sidebar">
<ul class="menu">
<div align="center">
<div class="logo">
<img src="/media/MainLogoNoBg.jpeg" width="90%"/>
    </div>
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
             $result = mysqli_query($con,$sql);
             if(mysqli_num_rows($result) === 1){
                $row = mysqli_fetch_assoc($result);
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
        <?php
            printf('
           <h1>PERSONAL INFO</h1>
           <div class="content1">
            <table class="content-table" border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <td>User ID :</td>
                    <td>%s</td>
                </tr>
                <tr>
                    <td>User Name :</td>
                    <td>%s</td>
                </tr>
                <tr>
                    <td>Email :</td>
                    <td>%s</td>
                </tr>
                <tr>
                    <td>Phone :</td>
                    <td>%s</td>
                 </tr>
            </table>
            <form action="" method="post">
                <input type="hidden" name="id" value="%s" />
                <input type="hidden" name="Name" value="%s" />
                <input type="hidden" name="email" value="%s" />
                <input type="hidden" name="phone" value="%s" />
                <div class="cancelbutton">
                <input type="button" class="cancel-btn" value="Edit Profile"
                        onclick="location=\'edit_User.php\'" /></div>
            </form></div>',
            $id, $name, $email, $phone,
            $id, $name,$email,$phone);
        
       ?>
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