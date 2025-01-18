<?php
    include('toolman.php');
    require_once('sqlcon.php');

    session_start();
    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ticket Booking</title>
    <link rel="stylesheet" href="../CSS/Booking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css"/>
    
    <style type="text/css">
      
      *{margin: 0;}
      a{color: white;}
      .debug{font-size: 20px;color: red;}
      
      .invalid{
          border-color: red;
          color: red;
          animation: flash 0.5s ease;
      }

      @keyframes flash {
        0% { background-color: red; }
        50% { background-color: white; }
        100% { background-color: red; }
      }

    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"> //Google hosted jQuery
    </script>
    <script src="../JAVAs/jquery-1.9.1.js">//Modify the <script> element to link to the local jQuery library file.
    </script>
    <script>
    function checkboxTotal(){
      var seat=[];
      $('input[name="seat[]"]:checked').each(function(){
        seat.push($(this).val());
      });

      var st = seat.length;
      var seatCount = document.getElementById('seatCount');
      seatCount.value = st;
      document.getElementById('hiddenSeatCount').value = st;
      if(seatCount.validity.rangeOverflow){
        $('#seatCount').addClass("invalid");
      }
      else{
        $('#seatCount').removeClass("invalid");
      }
      

      var count = $('#hiddenSeatCount').val();
      if(count > 4){
        $('#seatDetails').css("color", "red");
        //$('#seatCount').css("color","red");
      }
      else{
        $('#seatDetails').css("color", "black");
      }

      $('#seatDetails').val(seat.join(","));
      $('#hiddenSeat').val(seat.join(","));

    }

    function submitBook(){
      var count = $('#hiddenSeatCount').val();
      var name = $('#username').val();
      if(count < 1 || count > 4){
        alert('Please select 1-4 seats!!!')
      }
      else{
        if(confirm("Are you sure you want to book " + count + (count > 1 ? ' seats as ' : ' seat as ') + name + " ?")){
          $('#seatBook').submit();
        }
      }
    }
    
</script>
</head>
<body>
<?php
  include_once('header.php');

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
      $eid = isset($_GET['event'])? strtoupper(trim(validate($_GET['event']))) : null;
      $sql = "SELECT * FROM event_form WHERE EventID = '".$eid."'";
      $result = mysqli_query($con,$sql);

      if(mysqli_num_rows($result) === 1){
        $row = mysqli_fetch_assoc($result);
        $hideForm = false;
        $eid = $row['EventID'];
        $ename = $row['EventName'];
        $datetime = $row['Time'];
        $file = $row['file'];
      }
      else{
        $hideForm = true;
        $result->free();
        $con->close();
      }
  }

if (!($hideForm)){
///Hides this div
?>
<div align="center" class="title">
    <span>CHOOSE YOUR SEATS</span>
</br>
    <p align="left" style="padding-left: 20px;"><a href="Events.php">< Back</a></p>
</div>
<?php
}
?>
<div class="body">
<?php
    if(!($hideForm)){
?>
<div style="padding: 10px;">
 <h1>STAGE</h1>
  <form action="testpost.php" method="post" id="seatBook">
  <ol>
    <?php
    ///START FORM
    htmlInputHidden("username",$_SESSION['user_name']);
    htmlInputHidden("eventid",$eid);
    htmlInputHidden("eventid",$eid);
    htmlInputHidden("userid",$_SESSION['user_id']);
    htmlInputHidden("hiddenSeat","");
    htmlInputHidden("hiddenSeatCount","");

    ////////SPECIFY ROW COUNT AND COL COUNT
    $numRows = 8; //1234567890
    $numSeatsPerRow = 10; //ABCDEFGHIJ
    
    $booked = array(); 
    $sql = "SELECT seats_selected from booking where event_id = '$eid' AND NOT bookStatus = 'rejected'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {  
      while($row = $result->fetch_assoc()) {
          $seats = explode(',', $row['seats_selected']);           // Split the seats_selected string into individual values
          foreach ($seats as $seat) {
              $trimmed_seat = trim($seat);
              if (!empty($trimmed_seat)) {
                  $booked[] = $trimmed_seat;
              }
          }
      }
      $temp = implode(', ', $booked);
      //echo "<span class='debug'> Combined seats: " . $temp."</span>";
    } else {
        echo "No seats selected in bookings.";
    }

     // Loop through each row
     for ($row = 1; $row <= $numRows; $row++) {
      echo '<li class="row row--' . $row . '">';
      echo '<ol class="seats">';

      // Loop through each seat within the row
      for ($seat = 1; $seat <= $numSeatsPerRow; $seat++) {
          $seatId = $row . chr(64 + $seat); // Convert seat number to alphabet (1A, 1B, 1C, ...)
          $label = $row . chr(64 + $seat);  // Label same as seat number

          echo '<li class="seat">';
          echo '<input type="checkbox" id="' . $seatId . '" name="seat[]" value="' . $seatId . '" onclick="checkboxTotal()" ' . (in_array($seatId, $booked) ? 'disabled' : '') . '/>';
          echo '<label for="' . $seatId . '" class="seatselector">' . $label . '</label>';
          echo '</li>';
      }

      echo '</ol>';
      echo '</li>';
  }
    ?>
  </ol>
  <b>Legend:</b><br/><br/>
  <p class="legend" align="center">
    <span style="color:white;font-variant:small-caps;">Available</span>&emsp;
    <span style="color:red;font-variant:small-caps;">Unavailable</span>&emsp;
    <span style="color: greenyellow;font-variant:small-caps;">Selected</span>
  </p>
  </form>
  </div>
  <?php

  
  ?>
  <div class="bookform">
      <img class="eventPhoto" src="../Upload/<?php 
      echo isset($file)?$file:'../Media/post.png';
      ?>" />
      <br/>
      
      <p id="eventT">
        <?php
          echo $ename." (".$eid.")"
          ."<br>";
          echo formatDateTime($datetime)."<br><br>";
        ?>

      </p>
      <form id="spoof">
        <label>Count:</label>
        <input class="book" id='seatCount' type='number' value="0" max="4" readonly min="1"/>
        <label>Seats Selected:</label>
        <input class="book" type="text" id ='seatDetails' placeholder="1-4 Seats only/customer!" readonly/> 
      </form>
      <button onclick="submitBook()">Book</button>
  </div>
  <?php
    }else{
      echo "
      <div class='error' style='width:100%;'>
      <span>404</span> It may not be your fault, but there is nothing here!
      <br><br><br>
      <a href='Events.php'>[Back to Events]</a>
      <img src='../Media/404.png'/>
      </div>
      ";
    }
?>
</div>
<?php
include('footer.php');
?>

</body>
</html>
<?php
}
else{
  header("Location: Homepage.php?menu=login");
  exit();
}
    ?>