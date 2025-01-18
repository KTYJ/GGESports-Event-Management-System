<?php
    session_start();
    include('sqlcon.php');

    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style type="text/css">
       @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap");

    *{
        margin:0;
        padding:0;
        border: none;
        outline: none;
        box-sizing: border-box;
        font-family: "Poppins",sans-serif;
    }

    body{
        display:flex;
    }

    .sidebar{
        position: sticky;
        top: 0;
        left: 0;
        bottom: 0;
        width: 240px;
        height: 100vh;
        padding: 0 1.7em;
        color: #fff;
        overflow: hidden;
        transition: all 0.5 linear;
        background: rgb(33, 78, 105);
        padding-top: 30px;
    }

    .menu{
        height: 80%;
        position: relative;
        list-style: none;
        padding: 0;
    }

    .menu li{
        padding: 1rem;
        margin: 8px 0;
        border-radius: 8px;
        transition: all 0.5s ease-in-out;
    }

    .menu li:hover,
    .active{
        background-color: #e0e0e058;
    }

    .menu a{
        color:#fff;
        font-size: 14px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 1.5em;
    }

    .menu a span{
        overflow: hidden;
    }

    .menu a i{
        font-size: 1.2rem;
    }

    .content{
        background-color: #f5f5f5;
        position:relative;
        width: 100%;
        padding: 1rem;
    }

    .head{
        margin-top: 150px;
        text-align: center;
        font-size: 20px;
    }

    .content-1{
        padding-top: 100px;
        text-align: center;
    }

    .btn1{
        min-width:300px ;
        border: 1px solid black;
        background: none;
        padding: 12px 20px;
        font-size: 30px;
        cursor: pointer;
        margin: 10px;
        overflow: hidden;
        transition: 0.4s;
        position: relative;
    }

    .btn1:hover{
        color:#fff;
        background-color: lightseagreen;
        transition: color 0.4s linear;
    }

    .btn1::before{
        content: "";
        position: absolute;
        left: 0;
        background: #000;
        z-index: -1;
        transition: 0.8s;
        top: 0;
        transition: transform 0.5s;
        transform-origin: 0 0;
        transition-timing-function: cubic-bezier(0.5,1.6,0.4,0.7);
    }

    .btn1 i{
        padding-right: 20px;
    }

    .btn2{
        min-width:300px ;
        border: 1px solid black;
        background: none;
        padding: 12px 20px;
        font-size: 90px;
        cursor: pointer;
        margin: 10px;
        overflow: hidden;
        transition: 0.4s;
        position: relative;
    }

    .btn2:hover{
        color:#fff;
        background-color: lightseagreen;
        transition: color 0.4s linear;
    }

    .btn2::before{
        content: "";
        position: absolute;
        left: 0;
        background: #000;
        z-index: -1;
        transition: 0.8s;
        top: 0;
        transition: transform 0.5s;
        transform-origin: 0 0;
        transition-timing-function: cubic-bezier(0.5,1.6,0.4,0.7);
    }

    .btn2 i{
        padding-right: 20px;
    }

    
        

    
        
    </style>
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
             ?>
                <br/>
                SIGNED IN AS:
                <span id="aName">
                <?php
                        echo $_SESSION['user_name'].
                    "<br/>";
                    echo "ID: ".$_SESSION['user_id'];
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
            <div class="head">
            <h1>Welcome To User Page, <?php
                        echo $_SESSION['user_name'];?></h1>
            <div class="launch">
                <a href="Homepage.php">
                <button class="btn2"><i class="fa fa-rocket" aria-hidden="true"></i>LAUNCH WEB</button></a>
            </div>
            </div>
            
            <div class="content-1">
                <a href="booking_history.php">
                <button class="btn1"><i class="fa fa-calendar" aria-hidden="true"></i>Booking History</button></a>
                <a href="personal_info.php">
                <button class="btn1"><i class="fa fa-user" aria-hidden="true"></i>User Profile</button>
            </div>
        </div>
    </div>
    <!--Clock-->
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