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

     $headers = array(
        'EventName' => 'Event Name',
        'Time' => 'Time',

    ); 

    $sort  = !(isset($_GET['sort'])) ? 'eventname' : (array_key_exists($_GET['sort'], $headers) ? $_GET['sort'] : 'eventname');
    $order = !(isset($_GET['order'])) ? 'ASC' : ($_GET['order'] == 'DESC' ? 'DESC' : 'ASC');
    $src = isset($_GET['src']) ? ("%".$_GET['src']."%") : '%'; //Search query if exits
    $cleanedString = str_replace('%', '', $src);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        if(isset($_GET['src'])&&(!empty($_GET['src']))){
            echo "Showing results for".'"'. $_GET['src'].'"';
        }
        else{
            echo "Events";
        }
        ?>
    </title>

    <link rel="stylesheet" href="../CSS/PageLayout.css"/>
    <link rel="stylesheet" href="../CSS/Events.css"/>
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
    div.error{
        width: 80%;
        margin: 50px auto 0 auto;
        text-align: center;
        color: white;
        width: 100%;
    }
    .error span{
    font-size: 40px;
    display: block;
    }
    .error img{
    width: 20%;
    display: block;
    margin: auto;
    }
    a.desc{color: red;}
    a.asc{color: greenyellow;}

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
                <li><a class="active" href="../HTML/Events.php">Events</a></li>
                <li><a href="../HTML/Community.php">Community</a></li>
                <li><a href="../HTML/AboutUs.php">AboutUS</a></li>
            </ul>

    <div class="nav_action">
        <i class="ri-user-line nav__login" id="login-btn"></i>

    </div>
    </div>
</header>

<div class="login <?php
    if(isset($login) || (!empty($_POST))){
        echo "show-login";
    }
    else{
        echo "";
    }
?>"id="login">
    <form action="" class="login__form" method="post">
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
                You do not have an account ? <a href="#">Register</a>
            </p>

            <a href="forgot_password.php" class="login__forgot">
                Forgot Password ?
            </a>

            <button type="submit" class="login__button">Log In</button>
        </div>
    </form>

    <i class="ri-close-line login__close" id="login-close"></i>
</div>

<script src="../JAVAs/Login.js"></script>

<div class="block">
    <h2>EVENTS</h2>
    <p>Experience the thrill of esports with GGE!
        Book a seat today to watch our exciting tournaments
        featuring top players and intense matches.
        Cheer for your favorite teams and enjoy the 
        action live.
    </p>
    <div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
        <input type="text" placeholder="Search.." name="src">
        <button type="submit"><i class="ri-search-line"></i></button>
        <?php
        if(isset($_GET['src'])&&(!empty($_GET['src']))){
                echo 'Showing results for "'. $_GET['src'].'"';
        }
        ?>
    </form>
    <span>Sort by: | </span>
    <?php
        foreach ($headers as $key => $value) //'EventName' => 'Event Name(csgo)'
        {
            if ($key == $sort) // The sorted field.
            {                  //<img src="images/%s" alt="%s" />  $order == 'ASC' ? 'asc.png' : 'desc.png', Image
                printf('%s<a class="sort %s" href="?sort=%s&order=%s&src=%s">%s</a>',
                    $order == 'ASC'?'<i class="ri-arrow-up-line"></i>':'<i class="ri-arrow-down-line"></i>',
                    $order == 'ASC'?'asc':'desc',
                    $key,
                    $order == 'ASC' ? 'DESC' : 'ASC',
                    isset($_GET['src'])?$_GET['src']:'',
                    $value);
            }
            else // Non-sorted field.
            {
                printf('
                    <a href="?sort=%s&order=ASC&src=%s">%s</a>
                    ',
                    $key,
                    isset($_GET['src'])? $_GET['src'] : '',
                    $value);
            }
            echo " | ";
        }
        printf('<br><a href="?sort=%s&order=%s&src=%s">%s</a>',    
        $sort,
        $order,
        '',
        'Clear Search'
        );
    ?>
  </div>
</div>
</div>
<div class="flex-container">
    <!--Start loop-->
    <?php
   //$con;
   $sql = "SELECT * FROM event_form WHERE EventName LIKE '". $src ."' ORDER BY ".$sort." ".$order;
   $result = mysqli_query($con,$sql);
   if(mysqli_num_rows($result) > 0){
    while($row = mysqli_fetch_assoc($result)){
        printf('
            <div class="box-area">
                <div class="img-top">
                    <img src="../%s" alt="%s">
                </div>
                <div class="describe">
                    </br>
                    <p class="title">%s</p>
                    <br>
                    <div class="title_s">
                    <i class="ri-time-line"></i>&nbsp;&nbsp;%s
                    </div>
                    <br>
                    <a href="Booking.php?event=%s"><button class="bookNow">Book Now</button></a>
                </div>
            </div>
        ',!(empty($row['file']))?("Upload/".$row['file']):'Media/post.png',$row['EventName'],$row['EventName'],formatDateTime($row['Time']),$row['EventID']
    );
    }
   }
   //No Events.
   else{
    if(isset($_GET['src'])){
        $msg = "Whoops! '". $cleanedString ."' returned 0 results.";
    }
    else{
        $msg = "Whoops! No events found.";
    }
    echo "
      <div class='error' style='width:100%;'>
      <span>".$msg."</span> More interesting events are on their way.
      <br><br><br>
      <img src='../Media/404.png'/>
      </div>
      ";
   }

    ?> 
    </div>
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