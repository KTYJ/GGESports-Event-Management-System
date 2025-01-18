<?php
    include 'sqlcon.php';
    function passError(){
        $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        global $psw,$cpsw,$email;
        $eq = "SELECT * FROM user WHERE email = '$email' ";
        $eres = $con->query($eq);
        $error = array();

        //Validation
        if($email == null){
            $error['email'] = "Email cannot be empty.";
        }else if($eres->num_rows <= 0){
            $error['email'] = 'Email not found!';
        }


        if($psw == null){
            $error['psw'] = "Please choose a new password.";
        }
        else if(!preg_match('/^.{8,16}$/',$psw)){
            $error['psw'] = 'Your NEW password should have <b>8-16 characters</b>.';
        }
        else if($cpsw == null){
            $error['psw'] = 'Please <b>Confirm</b> your password.';
        }
        else if((strcmp($psw,$cpsw) != 0)){
            $error['psw'] = 'Passwords do not match, try again.';
        }


        $eres->free();
        return $error;
    }
    
    function checkError($error){
        printf("
            <div id='error'>
            <h1>&#9888; Oops!</h1>
            <ul><li>%s</li></ul></div>",
            implode('</li><li>',$error)
        );
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{
            font-family: Geneva, Verdana, sans-serif;
        }

        #error{
            background: rgb(255, 216, 216);
            font-family:sans-serif !important;
            color: red;
            padding: 10px;
            margin: 10px 5px;
            border-radius: 3px;
        }
        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 10px;
            width: 25%;
        }
        .form-container h3 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container input,
        .form-container input[type="submit"],
        .form-container a {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: block;
            text-align: center;
            box-sizing: border-box;
            color: #000;
        }
        .form-container input[type="submit"] {
            
            background-color: #214e69;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        .form-container input[type="submit"]:hover {
            background-color: #185b7f;
        }
        .form-container a {
            color: #214e69;
            text-decoration: none;
            text-align: center;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        label {
        color: black;
        }  
        .homeVideo {
            height: 100vh;
            width: 100vw;
            object-fit: cover;
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            z-index: -1;
        }
    </style>
    <title>Forget Password?</title>
</head>
<body>
    <video loop autoplay muted class="homeVideo">
        <source src="../Media/video (2160p).mp4" type="video/mp4"/>
    </video>
    <div class="form-container">
    <h3>Reset Password</h3>
        <?php
            if(isset($_POST['submit'])){
                $email = trim($_POST['email']);
                $psw = trim($_POST['password']);
                $cpsw = trim($_POST['cpassword']);
                $spsw = sha1($psw);//SHA1 encryption
                
                $error = array_filter(passError());
                if(empty($error)){
                    $sql = "UPDATE user SET password = '$spsw' WHERE email = '$email'";
                    mysqli_query($con, $sql);
                    if(mysqli_affected_rows($con) > 0){
                        echo '<p >Your password has been updated. 
                        <a href="Homepage.php?menu=login">Back to homepage</a>
                        </p>';
                    }
                    else{
                        echo '<p>Error with database. Password not changed.</p>';
                    }

                }
                else{
                    checkError($error);
                }
            }
        ?>
            <form action="" method="post">
            <div class="input-box">
                <label for="email">Email</label>
                <input type="text" name="email" placeholder="Enter your EMAIL">
            </div>
            <div class="input-box">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="8-16 characters" />
            </div>

            <div class="input-box">
                <label for="cpassword">Confirm Password</label>
                <input type="password" name="cpassword" placeholder="Confirm your password" />
            </div>
            <input type="submit" name="submit" value="Send" class="form-btn">
            <a href="Homepage.php" class="button">Back</a>
        </form>
    </div>
</body>
</html>
