<?php

session_start();
if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
#insert webpage content below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="../CSS/post.css" rel="stylesheet">
    <title>LOGIN SUCCESSFUL</title>
</head>
<body>
    <section>
        <div class="content">
            <img src="../Media/MainLogoNoBg.jpeg" width="140px"><br/>
            <img src="../Media/tick.png" id="tick"/>
            <h2>LOGIN SUCCESSFUL</h2>
            <p>User: <?php echo $_SESSION['user_id'];?></p>
            <p>You have sucessfully signed into your account!</p><!--PHP-->
            <br/>
            <span><a href="user.php">-----GO TO MY DASHBOARD------</a>
            </span>
        </div>
    </section>
</body>
</html>
<?php
}
else{
    session_unset();
    session_destroy();
    header("Location: Homepage.php");
    exit();
}
?>