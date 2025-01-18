<?php
    session_start();
    if(isset($_SESSION['admin_id'])){
?>
<?php
include 'additionalhelp.php';
include('sqlcon.php');
function detectEditError(){
    $con = new mysqli('localhost', 'root', '', 'gge');

    global $adminId, $adminname,$password,$email,$file,$error,$save_as;
    $error = array();

    $eq = "SELECT * FROM admin WHERE email = '$email' AND NOT adminId = '$adminId'";
    $eres = $con->query($eq);

    $idq = "SELECT * FROM admin WHERE adminId = '$adminId' ";
    $idres = $con->query($idq);


    //NAME//
    if($adminname == null){
        $error['name'] = 'Please fill in your <b>NAME</b>';
    }
    else if (!preg_match('/^[A-Za-z@\/\- ]+$/', $adminname))
        {
            $error['name'] = '<strong>Invalid characters</strong> were found in your name.';
    }
    elseif(strlen($adminname)>40){
        $error['name'] = 'Your name is too long, please make it <b>less than 40 characters.</b>';
    }

    //Email//    
    if($email == null) $error['email'] = 'Please enter your <b>EMAIL</b>';
    else if($eres->num_rows > 0){
        $error['email'] = 'Email has already been registered. <b>Proceed to login.</b>';
    }
    else if(!preg_match('/^[\w\-\.]+@[a-zA-Z\d\-]+(\.[a-zA-Z\d\-]+)+$/',$email)){
        $error['email'] = '<b>INVALID</b> email entered'; 
    }

    //Password
        if($password == null){
            $error['psw'] = 'Please enter a <b>PASSWORD</b>';
        }
        else if(!preg_match('/^.{5,16}$/',$password)){
            $error['psw'] = 'Your password is <b>invalid</b>, it should have <b>5-16 characters</b>.';
        }


    return $error;
    $idres->free();
    $eres->free();
    $con->close(); 
}

if($conn == false){
    die("Connection Error:". mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'GET'){ //I update
    $adminId = isset($_GET['adminId']) ? strtoupper(trim($_GET['adminId'])) : null;

    $adminId = $conn->real_escape_string($adminId);
    $sql = "SELECT * FROM admin WHERE adminId = '".$adminId."'";

    $result = $conn->query($sql);

    if ($row = $result->fetch_object()){
        $hideForm  = false; //Flag, "false" to show the form
        $adminId   = $row->adminId;
        $adminname = $row->adminname;
        $password  = $row->password;
        $email     = $row->email;
        $file      = $row->file;
    }
    else{

        $hideForm = true; //FLAG, "true" to hide the form
    }

    $result->free();
    $conn->close();
}

if(!empty($_POST)){
    $hideForm = false;

    $adminId   = strtoupper(trim($_POST['id']));
    $adminname = trim($_POST['name']);
    $password  = trim($_POST['pswd']);
    $email     = trim($_POST['email']);
    $file = trim($_POST['ofile']);

    $error = detectEditError();  //Validation
    $error = array_filter($error); //Bye bye null values.

    if(file_exists($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])){
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
                $newFile = 1; //flag to detect if new file is uploaded
            }
        }
    }
    else{
        $save_as = $file;
        $newFile = 0;
    }
    $error = array_filter($error); //Bye bye null values.
    if (empty($error)) // data validation passed
    {
        $conn = new mysqli('localhost', 'root', '', 'gge');
        
        $sql = '
        UPDATE admin SET 
        adminname = ?, 
        password = ?,
        email = ?,
        file = ?
        WHERE 
        adminId = ?
        ';
        $spsw = sha1($password);
        $stm = $conn->prepare($sql);
        $stm->bind_param('sssss', $adminname, $spsw, $email, $save_as,$adminId);
        
        if($stm->execute()) // update success
        {
            if($newFile){
                move_uploaded_file($file['tmp_name'], '../Upload/' . $save_as);
            }            
            printf('
            <div class="info">
            <img src="/media/tick.jpg">
            <h2> Admin Profile <b>%s</strong> has been updated.</h2>
            <input type="button" value="OK" class="info_button" onclick="location=\'adminh.php\'"/></div>',
            $adminId);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/editprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Edit Profile</title>
</head>
<body>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<div class="sidebar">
<ul class="menu">
<div class="logo">
        <img src="/media/GGE.jpg" width="90%"/>
        <br/>
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
        <!--PROFILE PICTURE--------------------------------------------------------->
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
            <strong>Welcome To Edit ADMIN Page</strong>
    </div>
    <div class="main-content">
    
<?php
    if($hideForm == true){
        echo '<div class="error">
        Opps. Record not found.
        [ <a href="admin.php">Back to PAGE</a> ]
        </div>';
    }else{

        if(!(empty($error))){
            printf("
            <div id='error'>
            <h2>&#9888; Error occured while editing profile</h2>
            <ul><li>%s</li></ul></div>",
            implode('</li><li>',$error)
            );
        }
?>
    <form action="" method="post"  enctype="multipart/form-data">
        <table>
            <tr>
                <td colspan="2" id="tablehead">Edit Admin Profile</td>
            </tr>
            <tr>
                <td class="label"><label for id="adminId">Admin ID :</label></td>
                <td>
                    <?php echo $adminId ?>
                    <?php htmlInputHidden('id', $adminId) // Hidden field. ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    <label for id="name">Name* :</label>
                </td>
                <td>
                    <?php
                    echo "<input type='text' name='name' value='$adminname' maxlength='30' required>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    <label for id="email">Email* :</label>
                </td>
                <td>
                    <?php
                    echo "<input type='text' name='email' value='$email' maxlength='30' required>";
                    ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    <label for id="pswd">Password* :</label>
                </td>
                <td>
                    <?php htmlInputPassword('pswd',"",15) ?>
                </td>
            </tr>
            <tr>
                <td class="label">
                    <label class="form-label" for id="file">Profile Picture :</label>
                </td>
                <td>
                    <?php htmlInputHidden('ofile', (isset($newFile)?$save_as:$file))?>
                    <div class="upload" style="text-align: center;">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                            <input style="width: 90%;" class="form-control" type="file" name="file" id="file" />                            
                    </div>
                </td>
            </tr>
        </table>
        <div class="click">
            <button type="submit" name="submit" value="Submit" class="button_submit">
            <span class="button_textsubmit">Submit</span>
            <span class="button_iconsubmit"><ion-icon name="cloud-upload-outline"></ion-icon></span></button>

            <button type="button" name="cancel" value="Cancel" class="button_cancel" onclick="location.href='admin.php'">
            <span class="button_textcancel">Cancel</span>
            <span class="button_iconcancel"><ion-icon name="close"></ion-icon></span></button>
        </div>
    </form>
    <?php
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
<?php
    }   else{
        header("Location: Homepage.php");
        exit();
    }
?>
</body>
</html>

