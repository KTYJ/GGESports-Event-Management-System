<?php
    ////TESTPOST.PHP - once booking is sucessful
    date_default_timezone_set('Asia/Kuala_Lumpur');
    require_once('sqlcon.php');
    include('toolman.php');

    session_start();
    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
        if(!empty($_POST)){
            $st = isset($_POST['seat']) ? $_POST['seat'] : null ;
            $seats = isset($_POST['hiddenSeatCount']) ? $_POST['hiddenSeatCount'] : null ;
            if($st == null || ($seats < 1 || $seats > 4)){ //Seat number should be around 1-4
                    echo "Oops..Invalid seats";
                    echo "<a href='Booking.php?event=".$_POST['eventid']."'>Back to booking</a>";
            }
            else{
                if(isset($_POST['hiddenSeat'])){
                    $seat = $_POST['hiddenSeat'];
                    $user = $_POST['userid'];
                    $eventid = $_POST['eventid'];
                    $bookid = generateUniqueID($con);
                    
                    $sql = "SELECT seats_selected,event_id FROM booking WHERE seats_selected = '$seat' AND event_id = '$eventid'";
                    $sres = $con ->query($sql);
                    if($sres->num_rows > 0){
                        $dupeError = 'Record already exists!'; 
                    }
                    
                }
            }
     }
    }else{
        header("Location: Homepage.php?menu=login");
        exit();
    }
?>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="../CSS/post.css" rel="stylesheet">
        <title>GGE Booking</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
        <style type=text/css>
            table.bookdetails{
                border: none;
                color: white;
                width: 100%;
                margin: 20px 0;
            }
            .bookdetails caption{
                text-decoration: underline;
                font-family: Helvetica, Arial, sans-serif;

            }
            .bookdetails td{
                padding: 8px 2px;
                font-family: Helvetica, Arial, sans-serif;
            }
            td.key{
                width: 20%;
                font-weight: bold;
                text-align: right;
            }
            td.value{
                padding-left: 10px;
            }
            @media print {
            /* All your print styles go here */
                *{
                    color: black;
                }
                .content span, .status {
                    display: none;
                }
            }
        </style>
    </head>
    <section>
        <div class="content">
            <img src="../Media/MainLogoNoBg.jpeg" width="140px"><br/>
            <?php
                $addbook = "INSERT INTO booking values ('$bookid','$eventid','$user','$seat','pending')";
                if(empty($dupeError)){  //if empty no duplicate record error
                    mysqli_query($con, $addbook);
                    if(mysqli_affected_rows($con) > 0){
                        $bookSucess = 1;
                    }
                    else{
                        $bookSucess = 0;
                    }   
                }else{      //else there is duplicate, give error
                    $bookSucess = 0;
                }
                printf('<img class="status" 
                        src="../Media/%s" 
                        id="tick"/>
                        <h2>%s</h2>
                        
                        ',
                ($bookSucess)?"tick.png":"cross.png",
                ($bookSucess)? "BOOKING SUCCESSFUL":"OOPS!"
                );
                if ($bookSucess)
                {
            ?>
            <table class="bookdetails">
            <caption>Your Booking Details</caption>
            <tbody>
                <tr>
                    <td class="key">Order ID:</td>
                    <td class="value"><?php echo $bookid;?></td>
                </tr>
                <tr>
                    <td class="key">Name:</td>
                    <td class="value"><?php 
                    $sql = "SELECT phone FROM user WHERE userId = '$user'";
                    $result = mysqli_query($con,$sql);
                    if(mysqli_num_rows($result) === 1){
                        $row = mysqli_fetch_assoc($result);
                        $name = $_SESSION['user_name'];
                        $phone = $row['phone'];
                    //Name
                        echo $name." (".$phone.")";
                        echo "</td></tr><tr><td class='value'>"; //Time
                    }
                    $result->free();
                    ?></td>
                    
                </tr>
                <tr>
                    <td rowspan="2" class="key">Event:</td>
                    <td class="value"><?php
                            $sql = "SELECT EventName,Time FROM event_form WHERE EventID = '$eventid'";
                            $result = mysqli_query($con,$sql);
                            if(mysqli_num_rows($result) === 1){
                                $row = mysqli_fetch_assoc($result);
                                $eventname = $row['EventName'];
                                $date = $row['Time'];
                            //Name
                                echo $eventname." (".$eventid.")";
                                echo "</td></tr><tr><td class='value'>"; //Time
                                echo formatDateTime($date);
                            }
                            $result->free();
                            $con->close();
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">Seats:</td>
                    <td class="value"><?php echo $seat;?></td>
                </tr>
                <tr>
                    <td class="key">Booking Date:</td>
                    <td class="value">
                        <?php echo date("F j Y h:i:s A");?>
                    </td>
                </tr>
            </tbody>
            <br/>
            </table>
            <span>
                <a href="user.php">TO DASHBOARD</a>
                |
                <a style="cursor: pointer;" onclick="window.print();"><i class="ri-printer-fill"></i> PRINT RECEIPT</a>
            </span>
            <?php
                }
                else{
                    #book fail
                echo "<p>An error occured!
                        <br><br><a href='Homepage.php'>BACK TO HOME</a>
                     </p>";
                }
            ?>
        </div>
    </section>
</html>