<?php
$conn = mysqli_connect("localhost","root","","gge");

$errors = array();

date_default_timezone_set('Asia/Kuala_Lumpur');

if(isset($_POST["submit"])){
    
    // Validate name, comment, date
    if (empty($_POST["name"])) {
        $errors[] = "Name is required !!!";
    } else {
        $name = $_POST["name"];
    }

    if (empty($_POST["comment"])) {
        $errors[] = "Comment is required !!!";
    } else {
        $comment = $_POST["comment"];
    }

    if (empty($errors)) {
        // If there are no errors, proceed to insert database
        
        $date = date('F d Y, h:i:s A');
        $reply_id = $_POST["reply_id"];

        $query = "INSERT INTO tb_data VALUES('','$name','$comment','$date','$reply_id')";
        mysqli_query($conn, $query);
    }
}
?>
<?php
    include ('toolman.php');
    require_once('sqlcon.php');

    #LOGIN PASSWORD FILL WITH ADMIN ID TO GO TO alogin.php


    session_start();
    
    global $login, $userin;
    $login = null;
    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
        #user is logged in
        $userin = 1;   
    }
    else{
        #not logged in, redirect from register.
        if (isset($_GET['menu'])){
            $login = strtoupper($_GET['menu']);
        }
        else{
            $login = null;  
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community</title>

    <link rel="stylesheet" href="../CSS/Community.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
    <link rel="stylesheet" href="../CSS/PageLayout.css">
    <style type="text/css">
    .login .error{
        background-color: rgb(254, 168, 168);
        padding: 10px 0;
        animation: flash 2s;
        border-radius: 5px;
        color: brown;
       
    }
    .login {

    height: 900px /*CHANGED*/
    }
    .error a{
        color: inherit;
    }

    @keyframes flash {
            0%{background-color: Red}
            30%{background-color: rgb(254, 168, 168);}
    }    
</style>
</head>
<body>
<header>
    <div class="icon-bar">
        <a href="../HTML/Homepage.php"><img src="../Media/MainLogo.jpeg" alt="" width="110px" /></a>

            <ul>
                <li><a href="../HTML/Homepage.php">Home</a></li>
                <li><a href="../HTML/Games.php">Games</a></li>
                <li><a href="../HTML/Events.php">Events</a></li>
                <li><a class="active" href="../HTML/Community.php">Community</a></li>
                <li><a href="../HTML/AboutUs.php">AboutUS</a></li>
            </ul>

    <div class="nav_action">
        <i class="ri-user-line nav__login" id="login-btn"></i>

    </div>
    </div>
</header>

<div class="login <?php
    if(isset($_POST['id']) && isset($_POST['psw'])){
        echo "show-login";
    }
    else{
        echo "";
    }
?>"id="login">    <form action="" class="login__form" method="post">
        <h2 class="login__title">Log In</h2>

        <div class="login__group">
        <?php
                $con = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
                #get variables after login attempt
                if(isset($_POST['id']) && isset($_POST['psw'])){
                    $id = validate($_POST['id']);
                    $psw = validate($_POST['psw']); //Prevent hacks                    

                $spsw = sha1($psw);
                $sql = "SELECT * FROM user WHERE userId='$id' AND password='$spsw' ";
                $result = mysqli_query($con,$sql);

                                #admin
                if(strcmp($id,$psw) == 0){
                    $asql = "SELECT * FROM admin WHERE adminID='$id'";
                    $aresult = mysqli_query($con,$asql);
                    if(mysqli_num_rows($aresult) === 1){
                        header("Location: alogin.php");
                        exit();
                    }
                }
                #admin
                if(strcmp($id,$psw) == 0){
                    $asql = "SELECT * FROM admin WHERE adminID='$id'";
                    $aresult = mysqli_query($con,$asql);
                    if(mysqli_num_rows($aresult) === 1){
                        header("Location: alogin.php");
                        exit();
                    }
                }
                #admin end

                if(mysqli_num_rows($result) === 1){
                    $row = mysqli_fetch_assoc($result);
                    if($row['userId'] === $id && $row['password'] === $spsw){
                        $_SESSION['user_id'] = $row['userId'];
                        $_SESSION['user_name'] = $row['Name'];
                        header("Location: post.php");

                        $result -> free_result();
                        $con -> close();
                    }
                    else{
                        $error = 'Ops! Inccorect User ID or Password. ';
                    }
                }
                //When one or two fields are empty
                else if(empty($id) || empty($psw)){
                    $error = 'Ops! Please fill in all fields..';
                }
                else{
                    $error = 'Ops! Inccorect User ID or Password. ';
                }

                if(!empty($error)){ //login fail
                    printf("<div class='error'>
                    <p>&#9888; %s</p>
                    <p>Register for an account <a href='User_Register.php'>here...</a></p>
                    </div>",$error);
                }
            }
            ?>
            <div>
                <label for="id" class="login__label">ID</label>
                <input type="text" name="id" placeholder="Enter your ID" id="email" class="login__input">
            </div>

            <div>
                <label for="password" class="login__label">Password</label>
                <input name="psw" type="password" placeholder="Enter your password" id="password" class="login__input">
            </div>
        </div>

        <div>
            <p class="login__signup">
                You do not have an account ? <a href="User_Register.php">Register</a>
            </p>

            <a href="forgot_password.php" class="login__forgot">
                Forgot Password ?
            </a>

            <button type="submit" class="login__button">Log In</button>
        </div>
    </form>

    <i class="ri-close-line login__close" id="login-close"></i>
</div>

    <div class="container">
        <?php

        // Diplsay the errors, if any
        if (!empty($errors)) {
            echo "<div class = 'errors'>";
            foreach ($errors as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";
        }


        $datas = mysqli_query($conn,"SELECT * FROM tb_data WHERE reply_id = 0"); // only select comment, not include reply
        foreach($datas as $data) {
            require 'comment.php';
        }
        ?>
        <form action="" method="post">
            <h3 id="title">Leave a Comment</h3>
            <input type="hidden" name="reply_id" id="reply_id">
            <input type="text" name="name" placeholder="Your name">
            <textarea name="comment" id="comment" placeholder="Your comment" style="resize: none;" oninput="autoResize()"></textarea>
            <button type="submit" name="submit" class="submit">Submit</button>
        </form>
    </div>

    <script>
        function reply(id, name) {
            title = document.getElementById('title');
            title.innerHTML = "Reply to " + name;
            document.getElementById('reply_id').value = id;
        }

function autoResize() {
    const textarea = document.getElementById("comment");
    textarea.style.height = "auto"; // Reset the height to auto to recalculate the height based on content
    textarea.style.height = (textarea.scrollHeight) + "px"; // Set the height to match the content height
}


    </script>

<script>
    /*================ Login.js ============= */
    const login = document.getElementById('login'),
            loginBtn = document.getElementById('login-btn'),
                loginClose = document.getElementById('login-close')


    /*Log in show */
    loginBtn.addEventListener('click',() => {
        <?php
            echo isset($userin)?"window.location.href = 'user.php';":"login.classList.add('show-login')";
        ?>
    })


    /*Log in hidden */
    loginClose.addEventListener('click',()=>{
        login.classList.remove('show-login')
    })
</script>
<footer>
    <div class="main-content">
        <div class="left box">
            <h2>Navigation</h2>
            <div class="content">
                <ul>
                <a href="Homepage.php"><li>Home</li></a>
                    <a href="Games.php"><li>Games</li></a>
                    <a href="Events.php"><li>Events</li></a>
                    <a href="Community.php"><li>Community</li></a>
                    <a href="AboutUs.php"><li>About Us</li></a>
                </ul>
                <div class="social">
                    <a href=""><i class="ri-facebook-circle-line"></i></a>
                    <a href=""><i class="ri-twitter-x-fill"></i></a>
                    <a href=""><i class="ri-instagram-line"></i></a>
                    <a href=""><i class="ri-youtube-line"></i></a>
                </div>
            </div>
        </div>

        <div class="center box">
            <h2>Address</h2>
            <div class="content">
                <div class="place">
                    <span class="fas fa-map-marker-alt"></span>
                    <span class="text">TARUMT</span>
                </div>
                <div class="phone">
                    <span class="fas fa-phone-alt"></span>
                    <span class="text">+6012-345-6789</span>
                </div>
                <div class="email">
                    <span class="fas fa-envelope"></span>
                    <span class="text">abc@example.com</span>
                </div>
            </div>
        </div>

        <div class="right box">
            <h2>Contact Us</h2>
            <div class="content">
                <form action="">
                    <div class="email">
                        <div class="text">Email *</div>
                        <input type="email" required>
                    </div>
                    <div class="msg">
                        <div class="text">Message *</div>
                        <textarea cols="25" rows="2" required></textarea>
                    </div>
                    <div class="btn">
                        <button type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>

</body>
</html>