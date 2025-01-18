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
<html>
<head>
    <title>About Us</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../CSS/PageLayout.css"/>
    <link rel="stylesheet"href="../CSS/AboutUs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
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
        <a href="../HTML/Homepage.php"><img src="../Media/MainLogo.jpeg" alt="Logo" width="110px" /></a>

            <ul>
                <li><a href="../HTML/Homepage.php">Home</a></li>
                <li><a href="../HTML/Games.php">Games</a></li>
                <li><a href="../HTML/Events.php">Events</a></li>
                <li><a href="../HTML/Community.php">Community</></li>
                <li><a class="active" href="../HTML/AboutUs.php">AboutUS</a></li>
            </ul>

    <div class="nav_action">
        <i class="ri-user-line nav__login" id="login-btn"></i>

    </div>
    </div>
</header>


<div id="videoSize">
    <video loop autoplay muted class="homeVideo">
        <source src="../Media/video (1080p).mp4" type="video/mp4"/>
    </video>
<div class="content--container">

<h1 class="title">ABOUT OUR E-Sport Society</h1>
        <p class="society">Welcome to the dynamic world of e-sports, 
            where digital landscapes come alive with competition, camaraderie, and endless possibilities. 
            As you embark on your journey into this vibrant realm, allow us to introduce you to the captivating universe of electronic sports 
            and the rich tapestry of experiences that await you.
        </p>
        <br>
        <p class="society">
        E-sports, short for electronic sports, represents the intersection of gaming, technology, and community. 
        It encompasses a diverse array of competitive video game titles, ranging from fast-paced shooters to intricate strategy games, each offering its own unique challenges and rewards. 
        In these virtual arenas, players from across the globe converge to showcase their skills, strategy, and teamwork, captivating audiences with their prowess and passion.
        </p>
        <br>
        <p class="society">Its core, e-sports is more than just a pastime; it's a thriving ecosystem teeming with opportunities for growth, learning, and personal development. 
            As you delve deeper into the world of e-sports,  you'll discover a wealth of experiences waiting to be explored
        </p>
        <br>
        <p class="society">
        As you embark on your e-sports journey, we encourage you to approach it with an open mind and a spirit of curiosity. 
        Whether you're looking to compete at the highest levels, connect with like-minded individuals, or simply explore the vast and exciting world of gaming,
        there's something for everyone in the realm of e-sports.
        </p>
        <br>
        <p class="society">
        So, welcome aboard, fellow gamers! Prepare to embark on an exhilarating adventure filled with thrills, challenges, and unforgettable moments. 
        The world of e-sports awaits are you ready to dive in?
        </p>
</div>
</div>
<div class="login <?php
    if(isset($login) || (!empty($_POST))){
        echo "show-login";
    }
    else{
        echo "";
    }
?>"id="login">
    <form action="" method="post" class="login__form">
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
                <input type="password" name="psw" placeholder="Enter your password" id="password" class="login__input">
            </div>
        </div>

        <div>
            <p class="login__signup">
                You do not have an account ?  <a href="User_Register.php">Register</a>
            </p>

            <a href="forgot_password.php" class="login__forgot">
                Forgot Password ?
            </a>

            <button type="submit" class="login__button">Log In</button>
        </div>
    </form>

    <i class="ri-close-line login__close" id="login-close"></i>
</div>



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