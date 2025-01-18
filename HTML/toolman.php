<?php
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);

        return $data;
    }

    function formatDateTime($datetimeString) {
        // Parse the datetime string into a timestamp
        $timestamp = strtotime($datetimeString);
    
        // Create the desired date and time formats
        $formattedDate = date('F j Y', $timestamp); // Example: April 1 2024
        $formattedTime = date('h:i A', $timestamp);   // 01:44 PM
        //('H:i') = hour minute 
    
        // Combine the formatted date and time
        $formattedDateTime = $formattedDate . ', ' . $formattedTime;
    
        return $formattedDateTime;
    }

    function htmlInputHidden($name, $value = ''){
        printf('<input type="hidden" name="%s" id="%s" value="%s" />' . "\n",
      $name, $name, $value);
    }

    function generateUniqueID($conn) {
        do {
            $unique_id = uniqid(false);
            $sql = "SELECT booking_id FROM booking WHERE booking_id = '$unique_id'";
            $result = $conn->query($sql);
        } while ($result->num_rows > 0); // Loop until a unique ID is found
        
        return $unique_id;
    }


?>