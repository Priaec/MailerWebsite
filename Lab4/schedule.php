<?php
        session_start();
        $database = 'lab4db';
        $conn = mysqli_connect("localhost","root","",$database);
        //check if user is logged in
        $email = $conn->real_escape_string($_POST['email']);
        $message = $conn->real_escape_string($_POST['message']);
        $timeStamp = $conn->real_escape_string($_POST['timeStamp']);
        //check data input validation here
        //email validation
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        if(!$validEmail){
                die("Improper format of email");
        }

        $sent = false;
        $userID = $_SESSION['userID'];
        if (!$conn)
            die("Connection Failed:  " + mysqli_connect_error());
        //INSERT INTO user(username, password) VALUES('$username', '$password')
        $query = "INSERT INTO message(userid, emailaddress, message, timeStamp, sent) 
                VALUES('$userID', '$email', '$message', '$timeStamp', false)";
        mysqli_query($conn, $query);
        mysqli_close($conn);
        //display the message back to the user 
        exit("Went through Form");
?>