<?php
require_once('sqlcon.php');

function detectRegError(){
    $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    global $id, $name,$psw,$cpsw,$email,$phoneNo;
    $error = array();

    $eq = "SELECT * FROM user WHERE email = '$email' ";
    $eres = $con->query($eq);

    $idq = "SELECT * FROM user WHERE userId = '$id' ";
    $idres = $con->query($idq);


    //NAME//
    if($name == null){
        $error['name'] = 'Please fill in your <b>NAME</b>';
    }
    else if (!preg_match('/^[A-Za-z@\/\- ]+$/', $name))
        {
            $error['name'] = '<strong>Invalid characters</strong> were found in your name.';
    }
    elseif(strlen($name)>50){
        $error['name'] = 'Your name is too long, please make it <b>less than 50 characters.</b>';
    }

    //ID//
        if($id == null) $error['id'] = 'Please enter your <b>USERNAME</b>';
        else if($idres->num_rows > 0){
            $error['id'] = 'ID is already in user.';
        }
        else if (!preg_match('/^[A-Za-z0-9]{5,8}$/',$id)) {
            $error['id'] = 'Your username should consist <b>5-8 number and letters ONLY</b>';
        }
        else if (strlen($id) < 5 || strlen($id) > 8) {
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

    //Password OR cpassword//
        if($psw == null){
            $error['psw'] = 'Please enter a <b>PASSWORD</b>';
        }
        else if(!preg_match('/^.{8,16}$/',$psw)){
            $error['psw'] = 'Your password is <b>invalid</b>, it should have <b>8-16 characters</b>.';
        }
        else if($cpsw == null){
            $error['psw'] = 'Please <b>Confirm</b> your password.';
        }
        else if((strcmp($psw,$cpsw) != 0)){
            $error['psw'] = 'Passwords do not match, try again.';
        }

        //Phone No
        if ($phoneNo == null)
        {
            $error['phone'] = 'Please enter your <strong>mobile phone</strong> number.';
        }
        else if (!preg_match('/^01\d-\d{7,8}$/', $phoneNo))
        {
            $error['phone'] = 'Your <strong>mobile phone</strong> number is invalid. Format: 01x-xxxxxxx.';
        }    

    return $error;
    $con->close(); 
}

function sucessDiv($name,$id){ //////for register/////////////////////////////////
    printf("<div id='success' align='center'>
    <img src='../Media/tick.png' width='20px'/>
    <h1>%s, you have sucessfully registered as 
        <span>'%s'</span>
    </h1>
    <a href='Homepage.php?menu=login'>Back to homepage</a>
    </div>",
    $name,$id);
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGE User Registration</title>
    <link rel="stylesheet" href="../CSS/User_Register.css">
    <style type="text/css">
        /*Error*/
        #error{
            background: rgb(255, 216, 216);
            font-family:sans-serif !important;
            color: red;
            padding: 10px;
            margin: 10px 5px;
            border-radius: 3px;
        }
        #success{
            background: rgb(182, 249, 182);
            border-radius: 3px;
            padding: 10px;
            margin: 10px 5px;
        }
        input:focus, input:not([value=""]), input:valid{
            color: white;
        }

    </style>
</head>
<body>
    
    <section class="container">
        <header style="color: white;">SIGN UP FOR GGE TODAY!</header>
        
        <p align="center" style="color: white; font-size: 14px;">
            <a href="Homepage.php?menu=login">Signed up? Back to homepage</a>
        </p><br/>
        <p align="center" style="color: white; font-size: 14px;">
            <br/>
            <b>WHY GGE?</b><br/>
            Join GGE today and unlock a world of gaming excellence! 
            As a member, you'll gain access to exclusive gaming events, tournaments, 
            and community challenges. 
            <br/>Connect with fellow gamers, level up your skills, 
            and immerse yourself in a vibrant gaming community.
            <br/>Don't miss out on the excitement,
            sign up now and let the games begin!
        </p>

        <!--End Success-->
        <?php
        ////////get form values
        #if((isset($_POST['submit']))){     needs input type submit
        if(!empty($_POST)){
            $id = trim($_POST['id']);
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $psw = trim($_POST['password']);
            $cpsw = trim($_POST['cpassword']);
            $phoneNo = trim($_POST['phoneNo']);

            $error = detectRegError();  //Validation
            $error = array_filter($error); //Bye bye null values.

            if(empty($error)){

                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                
                $sql = '
                INSERT INTO user (userID, Name, phone, password,email)
                VALUES (?, ?, ?, ?, ?)
                ';
                
                $stm = $con->prepare($sql);
                $spsw = sha1($psw);

                $stm->bind_param('sssss', $id, $name, $phoneNo, $spsw, $email);

                $stm->execute();

                if($stm->affected_rows > 0) //Success add
                {     
                    sucessDiv($name,$id);


                    $id = $name = $phoneNo = $psw = $psw = $email = null;

                }
                else //Add fail
                {
                    echo "<div id='error'>
                    <h1>&#9888; Oops! Error with database. Please contact admins. </h1>
                         </div>
                        ";
                }

               
            }
            else{ //Validation errors
                printf("
                    <div id='error'>
                    <h1>&#9888; Oops! Check your registration</h1>
                    <ul><li>%s</li></ul></div>",
                    implode('</li><li>',$error)
                );
            }
            $con->close(); 

        }
    ?>


        <!--Form-->
        <form action="" class="form" method="post">
            <div class="input-box">
                <label style="color: white;">Preferred Name</label>
                <input type="text" name="name" placeholder="e.g Ken Lam"
                value="<?php echo isset($name)?$name: "" ?>"
                />
            </div>
            
            <div class="input-box">
                <label style="color: white;">Username/ID</label>
                <ul style="color: grey;">
                    <li> 5 - 8 characters</li>
                    <li> Letters and numbers only</li>
                </ul>
                <input type="text" name="id" placeholder="e.g. ken3556"
                value="<?php echo isset($id)? $id: "" ?>"
                />
            </div>

            <div class="input-box">
                <label style="color: white;">Email Address</label>
                <input type="email" name="email" placeholder="kenlam@gmail.com"
                value="<?php echo isset($email)?$email: "" ?>"/>
            </div>

            <div class="input-box">
                <label style="color: white;">Password</label>
                <input type="password" name="password" placeholder="8-16 characters"
                value="<?php echo isset($psw)?$psw: "" ?>"
                />
            </div>

            <div class="input-box">
                <label style="color: white;">Confirm Password</label>
                <input type="password" name="cpassword" placeholder="Confirm your password"
                />
            </div>

            <div class="column">
                <div class="input-box">
                    <label style="color: white;">Phone Number</label>
                    <input type="text" name="phoneNo" placeholder="Malaysian Format (e.g 012-xxxxxxxx)"
                    value="<?php echo isset($phoneNo)?$phoneNo: "" ?>"
                    />
                </div>
            </div>
               

            <button>Submit</button>
        </form>
    </section>
</body>
</html> 

            <!--<div class="input-box">
                <label>Birth Date</label>
                <input type="date" name="birthdate" placeholder="Enter birth date" required/>
            </div>
        </div>

        <div class="gender-box">
            <h3>Gender</h3>
            <div class="gender-option">
                <div class="gender">
                    <input type="radio" name="gender" id="check-male" checked/>
                    <label for="check-male">Male</label>
                </div>
                <div class="gender">
                    <input type="radio" name="gender" id="check-female" checked/>
                    <label for="check-female">Female</label>
                </div>
                <div class="gender">
                    <input type="radio" name="gender" id="check-other" checked/>
                    <label for="check-other">Prefer not to say</label>
                </div>
            </div>
        </div>-->