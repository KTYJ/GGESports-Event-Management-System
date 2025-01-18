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
    <title>Welcome to GGE</title>
    <link rel="stylesheet" href="../CSS/PageLayout.css"/>
    <link rel="stylesheet" href="../CSS/Homepage.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
    <script src="../JAVAs/jquery-1.9.1.js"></script>
</head>
<style type="text/css">
    .login .error{
        background-color: rgb(254, 168, 168);
        padding: 10px 0;
        animation: flash 2s;
        border-radius: 5px;
        color: brown;
        
    }
    .error a{
        color: inherit;
    }

    @keyframes flash {
            0%{background-color: Red}
            30%{background-color: rgb(254, 168, 168);}
    }    
</style>

<header>
    <div class="icon-bar">
        <a href="../HTML/Homepage.php"><img src="../Media/MainLogo.jpeg" alt="" width="110px" /></a>

            <ul>
                <li><a class="active" href="../HTML/Homepage.php">Home</a></li>
                <li><a href="../HTML/Games.php">Games</a></li>
                <li><a href="../HTML/Events.php">Events</a></li>
                <li><a href="../HTML/Community.php">Community</a></li>
                <li><a href="../HTML/AboutUs.php">AboutUS</a></li>
            </ul>

    <div class="nav_action">
        <i class="ri-user-line nav__login" id="login-btn"></i>
    </div>
    </div>
</header>


<body>
    
<div id="videoSize">
    <video loop autoplay muted class="homeVideo">
    <source src="../Media/video (2160p).mp4" type="video/mp4"/>
    </video>

    <div class="overlap"><h1 class="title-1"> Welcome to E-Sports Society </h1> 
        <p class="title-2">A Society that let YOUR Gaming Experience Started. <br>
        We provide a lot of Events, Tournaments and More. <br>
        <br>Have a nice tour  </p>

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
                #admin end

                if(mysqli_num_rows($result) === 1){
                    $row = mysqli_fetch_assoc($result);
                    if($row['userId'] === $id && $row['password'] === $spsw){
                        $_SESSION['user_id'] = $row['userId'];
                        $_SESSION['user_name'] = $row['Name'];
                        $result -> free_result();
                        header("Location: post.php");
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
                <input name="psw" type="password" placeholder="Enter your password" id="password" class="login__input" >
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

<script>
    /*======================== Login ============= */
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

<img src="../Media/Red&Blue.jpg" alt="Info Background" class="backgroundInfo">

<div class="info-container">
   <div class="special-news">
        <h1>Latest from GGE</h1>
        <div>
        <?php
        $sql = "SELECT * FROM event_form ORDER BY Time Asc";
        $result = $con->query($sql);  //$result->num_rows > 0
        if($result->num_rows > 0){
            echo '<script src="../JAVAs/jqHome.js"></script>';
            while($row = mysqli_fetch_assoc($result)){
                printf('<div class = "event">
                            <div id="img"><img src="../Upload/%s" alt="%s"></div>
                            <p><b>%s</b><br/>at %s</p>
                        </div>
                        ',
                        $row['file'],$row['EventName'],
                        $row['EventName'],formatDateTime($row['Time'])
                        );
            }
        }
            echo "<div class='event cs'>
                    <p align='center'>More events coming Soon!<br>
                    <img src = '../Media/cscat.gif'/>
                    </p>
                 </div>";
        



        $con -> close();
        ?>
        </div>

    </div>
    
        <a href="Games.php"><div class="selection--menu">
            <img src="../Media/Games.jpg" class="nav-img" id="games-img"/>
            <div class="imgOverlay">GAMES</div>
            </div></a>

            <a href="Events.php">
            <div class="selection--menu">
            <img src="../Media/Event.jpg" class="nav-img" id="event-img"/>
            <div class="imgOverlay">EVENTS</div>
            </div></a>

            <a href="Community.php">
            <div class="selection--menu">
            <img src="../Media/Community.jpeg" class="nav-img" id="comm-img"/>
            <div class="imgOverlay">COMMUNITY</div>
            </div></a>

            <a href="AboutUs.php">
            <div class="selection--menu">
            <img src="../Media/AboutUs.jpg" class="nav-img" id="about-img"/>
            <div class="imgOverlay">ABOUT US</div>
            </div></a>

</div>
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
                    <div>
                        <button type="submit">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</footer>
</body>

</html>