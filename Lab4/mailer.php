<?php
    //script is to check if there are any messages to be sent at the current half hour
    //if there are any, then check if the messages have not been sent. If they have not,
    //then send them
    session_start();
    $database = "lab4db";
    $conn = mysqli_connect("localhost","root","",$database);
    //query not finished
    //get todays date
    //get local time zone
    date_default_timezone_set('America/Los_Angeles');
    $currentDate = date("Y-m-d H:i:s");
    //echo $currentDate;
    //$currentDate = date("Y-m-d H:i:s", strtotime($currentDate));
    //echo $currentDate;
    //get date + 29 minutes
    $endDate = date("Y-m-d H:i:s", strtotime('+29 minute',strtotime($currentDate)));
    //$endDate = date_add($currentDate,date_interval_create_from_date_string("29 minutes"));
    //echo $endDate;
    //SELECT * FROM message WHERE timestamp >='2022-12-02 13:00:00' AND timestamp <= '2022-12-02 13:00:00' + INTERVAL '29' minute AND sent = false;
    //$messageQuery = "SELECT * FROM message WHERE timestamp >= '$currentDate' ";

    $messageQuery = "SELECT * FROM message WHERE timestamp <= SYSDATE() AND sent = false";
    //THIS IS THE QUERY CURRENTLY USED FOR CURRENT VERSION$messageQuery = "SELECT * FROM message WHERE timestamp BETWEEN SYSDATE() AND ADDTIME(SYSDATE(),'00:29') AND sent = false";
    
    
    //$messageQuery = "SELECT * FROM message WHERE timestamp BETWEEN $currentDate AND DATEADD(minute,29,$currentDate) AND send = false";
    //result for message query...these are the ones to be sent
     echo serialize($messageResult = mysqli_query($conn, $messageQuery));
    mysqli_close($conn);
    if(!$messageResult->num_rows > 0){
        echo "no messges were found to be sent";
    }
    else{
        //iterate through selected messages
        while($row = $messageResult->fetch_assoc()){
            //get the current user that the specified element is reffering to 
            $currentUser = $row['userid'];
            $msgID = $row['msgid'];
            $email = $row['emailaddress'];
            $message = $row['message'];
            $subject;
            //need to run subquery for user
            $username = "";
            //$users['userID'] = $currentUser;
            //if email address is not valid, skip over
            $conn2 = mysqli_connect("localhost","root","",$database);
            $userQuery = "SELECT * FROM user WHERE userID = '$currentUser' limit 1";
            $userResult = mysqli_query($conn2, $userQuery);
            mysqli_close($conn2);
            if(!$userResult->num_rows > 0){
                echo "User that constructed message: " . $msgID ."was not found";
            }
            else{

                $userRow = $userResult->fetch_assoc();
                //get username from query    
                $username = $userRow['username'];
            }
            $subject = "Lab4 Test from: " . $username;
            echo $email . $subject . $message;
            $mailSend = mail($email, $subject, $message);
            if ($mailSend)
                echo "Mail Sent";
            //run update query for the sent message
            if (!$mailSend){
                echo "Mail Did Not Go Through for mail: " . $msgID;
                continue;
            }
            //we know it sent here
            $conn3 = mysqli_connect("localhost","root","",$database);
            $updateQuery = "UPDATE message SET sent = true WHERE msgID = '$msgID'";
            $updateResuult = mysqli_query($conn3, $updateQuery);    
            mysqli_close($conn3);
        }
        //we know we are done with database so we close connection here
        //mysqli_close($conn);
        echo "Finished mail processing for time: " . $currentDate;
    }
?>
