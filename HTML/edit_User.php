
<?php
    session_start();
    $con = new mysqli('localhost', 'root', '', 'gge');
    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
?>
<?php
function htmlInputText($name, $value, $maxLength) {
    echo "<input type='text' name='$name' value='$value' maxlength='$maxLength'>";
}

function htmlInputHidden($name, $value) {
    echo "<input type='hidden' name='$name' value='$value'>";
}
?>

<?php
function detectEditError(){
    $con = new mysqli('localhost', 'root', '', 'gge');

    global $userId, $Name,$phone,$password,$email,$file,$error,$cpassword,$opassword,$save_as;
    $error = array();
    $shaop = sha1($opassword);

    $currentid = $_SESSION['user_id'];

    $eq = "SELECT * FROM user WHERE email = '$email' AND NOT userId = '$currentid' ";
    $eres = $con->query($eq);

    $idq = "SELECT * FROM user WHERE userId = '$userId' AND NOT userId = '$currentid'";
    $idres = $con->query($idq);

    $opq = "SELECT * FROM user WHERE password = '$shaop' ";
    $opqres = $con->query($opq);

    //NAME//
    if($Name == null){
        $error['name'] = 'Please fill in your <b>NAME</b>';
    }
    else if (!preg_match('/^[A-Za-z@\/\- ]+$/', $Name))
        {
            $error['name'] = '<strong>Invalid characters</strong> were found in your name.';
    }
    elseif(strlen($Name)>40){
        $error['name'] = 'Your name is too long, please make it <b>less than 40 characters.</b>';
    }
    //userid
    if($userId == null) $error['id'] = 'Please enter your <b>USERNAME</b>';
    else if($idres->num_rows > 0){
        $error['id'] = 'ID is already in use.';
    }
    else if (!preg_match('/^[A-Za-z0-9]{5,8}$/',$userId)) {
        $error['id'] = 'Your username should consist <b>5-8 number and letters ONLY</b>';
    }
    else if (strlen($userId) < 5 || strlen($userId) > 8) {
        $error['id'] = 'Your username should have <b>5-8 CHARACTERS</b> ';
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
        if($cpassword == null || $password == null || $opassword == null ){
            $error['password'] = 'Please enter <b>password details</b>';
        }
        else if($opqres->num_rows < 1){
            $error['password'] = 'Your old password is incorrect.';
        }
        else if(!preg_match('/^.{8,16}$/',$password)){
            $error['password'] = 'Your password is <b>invalid</b>, it should have <b>8-16 characters</b>.';
        }
        else if((strcmp($password,$cpassword) != 0)){
            $error['psw'] = 'New passwords do not match, try again.';
        }

        //Phone No
        if ($phone == null)
        {
            $error['phone'] = 'Please enter your <strong>mobile phone</strong> number.';
        }
        else if (!preg_match('/^01\d-\d{7,8}$/', $phone))
        {
            $error['phone'] = 'Your <strong>mobile phone</strong> number is invalid. Format: 01x-xxxxxxx.';
        }    

    return $error;
    $idres->free();
    $eres->free();
    $con->close(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>EDIT PROFILE</title>
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../CSS/Edit_Profile.css" />
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
$PAGE_TITLE = 'Edit User';
include('sqlcon.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: homepage.php');
    exit;
}
?>

<div>
    <div class="form-container">
    <?php
                // Load the form
                $con = new mysqli('localhost','root', '', 'gge');
                $id = isset($_SESSION['user_id'])?trim($_SESSION['user_id']):NULL;
                $id  = $con->real_escape_string($id);
                $sql = "SELECT * FROM user WHERE userId = '".$id."'";
                $result = $con->query($sql);
                
                if ($row = $result->fetch_object()) {
                    $hideForm = 0; // Flag, "false" to show the form.
                    // Record found. Read field values.
                    $id       = $row->userId;
                    $oname     = $row->Name;
                    $ophone    = $row ->phone;
                    $oemail    = $row->email;
                    $ofile     = $row->file;
                }
                else {
                    echo '<div class="error">Opps. Record not found.</div>';
                    $hideForm = 1; // Flag, "true" to hide the form.
                }
                $result->free();
                $con->close();
                
            if(!empty($_POST)){ //something posted back
                $hideForm = 0;
            
                $userId       = trim($_POST['userId']);
                $Name         = trim($_POST['name']);
                $phone        = trim($_POST['phone']);
                $email        = trim($_POST['email']);
                $password     = trim($_POST['password']);
                $cpassword     = trim($_POST['cpassword']);
                $opassword     = trim($_POST['opassword']);
            
                $error = detectEditError();  //Validation
            
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
                            $newfile = 1;
                        }
                    }
                }
                else{
                    $save_as = ($ofile == null)?"":$ofile   ;
                    $newfile = 0;
                }
                
                $error = array_filter($error); //Bye bye null values.


                if (empty($error)) // data validation passed
                {
                    $conn = new mysqli('localhost', 'root', '', 'gge');
                    
                    $sql = '
                    UPDATE user SET 
                    userId = ?, 
                    Name = ?,
                    phone = ?,
                    password = ?,
                    email = ?,
                    file = ?
                    WHERE 
                    userId = ?  
                    ';
                    
                    $stm = $conn->prepare($sql);
                    $spsw = sha1($password);
                    $stm->bind_param('sssssss', $userId, $Name, $phone, $spsw,$email, $save_as, $id);
                    $stm->execute();
                    if($stm->affected_rows > 0) // update success
                    {
                        if($newfile){
                        move_uploaded_file($file['tmp_name'], '../Upload/' . $save_as);
                        }
                        $_SESSION['user_id'] = $userId;
                        $_SESSION['user_name'] = $Name;
                        printf('
                        <div class="info">
                        <img src="/media/tick.jpg">
                        <h2> User Profile <b>%s</strong> has been updated.</h2>
                        <input type="button" value="OK" class="info_button" onclick="location=\'user.php\'"/></div>',
                        $id);
                    }
                    else // update failed
                    {
                        echo '
                        <div class="error">
                        Opps. Database issue. Record not updated.
                        </div>
                        ';
                    }
                    
                    $conn->close();
                }
                else{   //GOT ERROR
                    printf("
                    <div id='error'>
                    <h2>&#9888; Error occured while editing profile</h2>
                    <ul><li>%s</li></ul></div>",
                    implode('</li><li>',$error)
                    );
                }
            }
        
            ?>
        <?php
            if ($hideForm) {
                    echo '<div class="error">
                    Opps. Record not found.
                    [ <a href="#">Back to PAGE</a> ]
                    </div>';
            }
            else{
             ?>
            <form action="" method="post" enctype="multipart/form-data">
            <h3 class="heading">Edit Profile</h3>
      
                <table cellpadding="5" cellspacing="0">
                    <tr>
                        <td><span><label for="userId">User ID :</label></span></td>               
                        <td>
                            <?php echo $id; htmlInputHidden('userId', $id) ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="name">User Preferred Name :</label></td>
                        <td>
                            <?php htmlInputText('name', $oname, 30) ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="phone">Phone No :</label></td>
                        <td>
                            <?php htmlInputText('phone', $ophone, 30) ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Email :</label></td>
                        <div class="border">
                        <td>
                            <?php htmlInputText('email', $oemail,30) ?>
                        </td>
                        </div>
                    </tr>
                    <tr>
                        <td><span><label for="password">Old Password :
                             </label></span>
                        </td>
                        <td>
                            <input type="password" name="opassword" id="opassword" placeholder="Previous password"/>
                        </td>
                    </tr>
                    <tr>
                        <td><span><label for="password">New Password :</label></span></td>
                        <td>
                            <input type="password" name="password" id="password" placeholder="8-16 characters"
                            value=""
                            />
                        </td>
                    </tr>
                    <tr>
                        <td><span><label for="password">Confirm Password :
                             </label></span>
                        </td>
                        <td>
                            <input type="password" name="cpassword"  id="cpassword" placeholder="Confirm your new password"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for class="form-label" id ="file">Profile Picture :</label>
                        </td>
                        <td>
                        <div class="upload">
                            <input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
                            <input class="form-control" type="file" name="file" id="file" />
                    </div>
                        </td>
                    </tr>
                   
                </table>
                <br />
                <input type="submit" class="form-btn" name="update" value="Update" />
                <button type="button" class="form-btn">
                <a href="personal_info.php" >Back</a></button>

            </form>
            <?php
    }
    ?>
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