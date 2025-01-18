<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>
<?php
include 'additionalhelp.php';
include('sqlcon.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Homepage</title>
    <link rel="stylesheet" href="../css/adminh.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poetsen One">
    <script src="../JAVAs/jquery-1.9.1.js">//Modify the <script> element to link to the local jQuery library file.</script>
    <script>
        $(document).ready(function(){
            $("#logout").click(function(){
            // Prompt the user to confirm logout
            if(confirm("Are you sure you want to logout?")) {
                window.location.href = "logout.php";
            }
            });
        });
    </script>

</head>
<body>

    <div class="container">
            <div class="image">
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
            </div>
            <div class="container2">
                <div class="htext">
                    <h2>Welcome,<br/>
                        <?php
                            $sql = "SELECT * FROM admin WHERE adminID='$adminid'";
                            $result = mysqli_query($con,$sql);
                            if(mysqli_num_rows($result) === 1){
                                $row = mysqli_fetch_assoc($result);
                                if(!empty($row['adminname'])){
                                   echo $row['adminname'];
                                }
                             }
                        ?></h2>
                </div>
                <div class="container3">
                    <ul>
                        <li>
                            <a href="admin.php">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <span>Manage Events</span></a>
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
                        <li id="logout">
                            <a href="#">
                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                            <span>LOG OUT</span></a>
                        </li>
                        <!--                        <li>
                            <a href="events_list.php">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <span>Events List</span></a>
                        </li>-->
                    </ul>
                </div>
                <div class="container4">
                    <ul>
                        <li>
                            <a href="manage_booking.php">
                            <i class="fa fa-pencil" aria-hidden="true"></i>
                            <span>Manage Booking</span></a>
                        </li>
                        <li>
                            <a href="userlist.php">
                                <i class="fa fa-users" aria-hidden="true"></i>
                                <span>User List</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
    </div>
</body>
</html>
<?php
    }else{
        header("Location: Homepage.php");
        exit();
    }
?>