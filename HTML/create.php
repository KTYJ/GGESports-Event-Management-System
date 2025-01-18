<?php
    session_start();
    date_default_timezone_set('Asia/Kuala_Lumpur');
    if(isset($_SESSION['admin_id'])){
?>
<?php
require 'additionalhelp.php';

  if(!empty($_POST)){
    $hideForm = false;

    $EventID   = strtoupper(trim($_POST['id']));
    $EventName = trim($_POST['name']);
    $Time  = trim($_POST['time']);

    $select = " SELECT * FROM event_form WHERE EventID = '$EventID'";
    $result = mysqli_query($conn,$select);

    if($EventID == null|| $EventName == null || $Time == null){
        $error['id'] = 'Please fill up <b>all fields</b>';
     }
     else if($result->num_rows > 0){
         $error['id'] = '<b>ID</b> is already exist';
     }
     else if (!preg_match('/^GGE\d{5}$/',$EventID)) {
         $error['id'] = 'Your Event ID should consist <b>GGEXXXXX</b> "X" can be any number';
     }
     else if($EventName == null){
       $error['name'] = 'Please enter <b>Event Name</b>';
     }




if(isset($_FILES['file'])){
        $file = $_FILES['file'];

        /*echo "<pre>";
        echo "This is from file<br>";
        print_r($_FILES);
        echo "This is from post<br>";
        print_r($_POST);
        echo "</pre>";*/
        if ($file['error'] > 0){
        // Check the error code.
        switch ($file['error']){
            case UPLOAD_ERR_NO_FILE: // Code = 4.
                $error['file'] = 'No file was selected.';
                break;
            case UPLOAD_ERR_FORM_SIZE: // Code = 2.
                 $error['file'] = 'File uploaded is too large. Maximum 2MB allowed.';
                break;
            default: // Other codes.
                $error['file'] = 'There was an error while uploading the file.';
                break;
        }
        }
        elseif($file['size'] > 2097152){
            // Check the file size. Prevent hacks.
            // 1MB = 1024KB = 1048576B.
            $error['file'] = 'File uploaded is too large. Maximum 2MB allowed.';
        }
        else{
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if ($ext != 'jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png')
            {
                $error['file'] = 'Only JPG, GIF and PNG format are allowed.';
            }
            else // everything ok, proceed to move the file
            {
                $save_as = uniqid().'.'.$ext; // new filename

            }
        }
    }
    else{
        $error['file'] = 'No file was selected (err.null).';

    }

    
    if(!isset($error)) // data validation passed
    {
        $insert = "INSERT INTO event_form(EventID,EventName,Time,file) VALUES(?,?,?,?)";
        $stm = $conn->prepare($insert);
        $stm->bind_param('ssss', $EventID, $EventName, $Time, $save_as);
        $stm->execute();
        
        if($stm->affected_rows > 0) // update success
        {
            move_uploaded_file($file['tmp_name'], '../Upload/' . $save_as);
            printf('
            <div class="info">
            <img src="/media/tick.jpg">
            <h2> Event <b>%s</strong> has been created.</h2>
            <input type="button" value="OK" class="info_button" onclick="location=\'admin.php\'"/></div>',
            $EventID);
        }
        else // update failed
        {
            echo '
            <div id="error">
            Opps. Database issue. Record not updated.
            </div>
            ';
        }
        
        $stm->close();
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Event</title>
    <link rel="stylesheet" href="/css/create.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            <strong>Add Event Page</strong>
    </div>
    <div class="main-content">
        <div class="forma">
    <form action="" method="post" enctype="multipart/form-data">
        <?php
          if(isset($error)){
            $error = array_filter($error); //Bye bye null values.
            printf("
            <div id='error'>
            <h1>&#9888; Oops!</h1>
            <ul><li>%s</li></ul></div>",
            implode('</li><li>',$error)
            );
          };
        
        ?>

        <table cellpadding="3" cellspacing="0">
            <tr>
                <td id="tablehead" colspan="2"><b>GGE ADMIN CONTROL PANEL</b></td>
            </tr>
            <tr>
                <td>
                    <label for="id">Event ID</label>
                </td>
                <td>
                    <input type="text" name="id" placeholder="GGExxxxx" autocomplete="off" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="eventName">Event Name</label>
                </td>
                <td>
                    <input type="text" name="name" placeholder="Enter Event Name" autocomplete="off" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="eventTime">Event Time</label>
                </td>
                <td>
                    <input type="datetime-local" name="time" autocomplete="off"
                    min="<?php echo date("Y-m-d ")."00:00:00";//Set minimum date to today?>" required>
                </td>
            </tr>
            <tr>
                <td>
                    <label class="form-label" for id="file">Event Picture :</label>
                </td>
                <td>
                    <div class="upload">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                            <input class="form-control" type="file" name="file" id="file" />
    
                    </div>
                </td>
            </tr>
        </table>
        <br/>
        <div class="click">
        <button type="submit" name="submit" value="Submit" class="button_submit">
        <span class="button_textsubmit">Submit</span>
        <span class="button_iconsubmit"><ion-icon name="cloud-upload-outline"></ion-icon></span></button>

        <button type="button" name="cancel" value="Cancel" class="button_cancel" onclick="location='admin.php'">
        <span class="button_textcancel">Cancel</span>
        <span class="button_iconcancel"><ion-icon name="arrow-back-circle-outline"></ion-icon></span></button>
        </div>
    </form>
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