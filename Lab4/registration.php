<?php
    //creation of user method
    session_start();
        //hashing algorithm
        $HASH_ALGO = 'sha512';
        $database = "lab4db";
        $conn = mysqli_connect("localhost","root","",$database);
        $username = $conn->real_escape_string($_POST['username']);
        //hash password here
        $password = $conn->real_escape_string($_POST['password']); 
        //we check user generated password here, take out the salt after transmission
        $userPW = substr($password, 6);
        //salt used to hash with
        $salt = substr($password,0,6);
        //check if valid pwd
        $validPwd = validatePassword($userPW, 8);
        if (!$validPwd)
            die("Error: Password is not correct");
        //eliminate usergenerated pw off mem
        $userPw = "";
        //we know password is in the clear here...
        $password = hash($HASH_ALGO,$password);
        //we will start running queries, check if we have a good connection
        if(!$conn)
            die("Connection Failed:  " + mysqli_connect_error()); 
        //we need to check if username is already taken
        //(we only need to know if there is one that exists minimum to deny requested user name)
        $usernameQuery = "SELECT * FROM user WHERE username = '$username' limit 1";
        //run query
        $userResult = mysqli_query($conn, $usernameQuery);
        //if there was at least 1 result from query, then we know someone has this username... we leave here
        if ($userResult->num_rows > 0) {
            mysqli_close($conn);
            die('Username Already Taken');
        }
        //username and password are both verified, run the insert query for user
        $query = "INSERT INTO user(username, password, salt) VALUES('$username', '$password', '$salt')";
        mysqli_query($conn,$query);
        mysqli_close($conn);
        echo "User Created";

        function validatePassword($password, $minLength){
            if($minLength <= 0) return false;
            $letter = preg_match('@[a-zA-Z]@', $password);
            $number = preg_match('@[0-9]@',$password);
            $specialChar = preg_match('@[^\w]@',$password);
            //check thru REGEX here!
            if(!$letter || !$number || !$specialChar || strlen($password) < $minLength)
                return false;
            //everything went through fine, we continue here
            return true;
        }

        function usernameTaken(){

            return false;
        }
?>

