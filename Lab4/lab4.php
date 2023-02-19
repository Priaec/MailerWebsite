<?php
        session_start();//
        $HASH_ALGORITHM = 'sha512';
        //log in to existing user method
        //check if its a match
        if(isset($_POST['login'])){
            //$username = $_POST['username'];
            //$password = $_POST['password'];
            $database = "lab4db";
            $conn = mysqli_connect("localhost","root","",$database);
            $username = $conn->real_escape_string($_POST['username']);
            //encryption method: SHA512
            $password = $conn->real_escape_string($_POST['password']);
            //fetch the salt for the username

            $saltquery = "SELECT salt FROM user WHERE username = '$username' limit 1";
            $saltResult = mysqli_query($conn, $saltquery);
            if(!$saltResult->num_rows > 0){
                mysqli_close($conn);
                die('Invalid Username or Passwor');
            }
            //we know we have a result, grab the salt
            $salt = $saltResult->fetch_assoc();
            $userSalt = $salt['salt'];
            $saltInput = $userSalt . $password;
            //no need to check password, were only comparing hashed values   
            //we will now hash the password before checking inside the database
            $password = hash($HASH_ALGORITHM, $saltInput);
            if(!$conn)
                die("Connection Failed:  " + mysqli_connect_error());
            $query = "SELECT userID, username FROM user WHERE username = '$username' AND password = '$password' limit 1";
            $result = mysqli_query($conn,$query);
            mysqli_close($conn);
            //display result
            //if we have result
            if($result->num_rows > 0){
                //fetching query results
                while($row = mysqli_fetch_array($result)){
                    $username = $row['username'];
                    $userID = $row['userID'];
                }
                echo "Welcome back: " . $username . "\n";
                $_SESSION['loggedIn'] = '1';//
                $_SESSION['username'] = $username;// 
                $_SESSION['userID'] = $userID;
                exit('login successful');
            }
            else{
                exit('Invalid Username and Password');
            }
        }
?>
<html>
    <head>
        <style>
            p{
                font-family: sans-serif;
            }

            .container{
                background-color: #40E0D0;
                border-radius: 20px;
            }

            .input{
                display: block;
                margin: 10px;
                border-radius: 3px;
                width: 200px;
            }

            .description{
                font-size: initial;
            }

            label{
                padding-left: 10px;
            }

            .button{
                margin: 10px;
                padding: 10px;
                border-radius: 7px;
                background-color: cadetblue;
            }

            .userFont{
                font-family: sans-serif;
            }

            .message{
                height: 300px;
            }
        </style>


        <title>User Registration in PHP</title>
        <!--JQuery library-->

    </head>
    <body>

        <div class="main">
            <form action="registration.php" method="post">
                <div class="container" id="registration-container">
                    <h1>Registration</h1>
                    <p class="description">Fill up the form with correct values</p>

                    <label for="username"><b>Username</b></label>
                    <input class="input" type="text" id="username" name="username" required>

                    <label for="password"><b>Password</b></label>
                    <input class='input' type="password" id="password" name="password" required>

                    <input class="button" id="create" type="button" name="create" value="Sign Up">
                    <p id="account-create"></p>

                </div>
            </form>
            <form action="lab4.php" method="post">
                <div class="container" id="login-container">
                    <h1>Login<h1>
                    <p class="description" style="font-size: initial; font-weight:100;">Already Have an Account?</p>

                    <label for="lusername"><b>Username</b></label>
                    <input class="input" type="text" id="lusername" name="lusername" required>

                    <label for="lpassword"><b>Password</b></label>
                    <input class='input' type="password" id="lpassword" name="lpassword" required>

                    <input class="button" type="button" id="login" name="login" value="Log In">
                </div>

            </form>
            <p id="logInResponse" class="userFont"></p>
            <form action="schedule.php" method="post">
                <div class="container" id="message-container">
                    <h1>Send Message</h1>
                    <label for="email"><b>Email Address</b></label>
                    <input class="input" id="email" type="email" name="email" required>

                    <label for="message"><b>Message</b></label>
                    <input class="input" id='message' style="height: 140px; width: 200px;" type="text" name="message" required>

                    <!--
                        <label for="time"><b>Time</b></label>
                        <input  class="input" id="time" type="datetime-local" name="time" required>
                    -->
               <!--Date and Time pickers-->
                <label for="date"><b>Date</b></label>
                <input class="input" id="date" type="date" name="date" required>
                
                <Label for="hour"><b>Hour</b></Label>
                <select id="hourPicker"></select>
                
                <label for="minute"><b>Minute</b></label>
                <select id="minutePicker"></select>
                    
                <label for="am-pm"><b>AM/PM</b></label>
                <select id="am-pmPicker">
                    <option value="AM">AM</option>
                    <option value="PM">PM</option>
                </select>

                <input class="button" type="button" id="schedule" name="schedule" value="Schedule">
                </div>    
            </form>
            <p id="displayMessage"></p>
            <!--Test button for mailer.php-->
            <form action="mailer.php" method="post">
                <input class="button" type="button" id="mailer" name="mailer" value="send emails">
            </form>
        </div>

        <script
            src="https://code.jquery.com/jquery-3.6.1.min.js"
            integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
            crossorigin="anonymous">
        </script>

        <script type="text/javascript">

            let registrationDiv = document.getElementById('registration-container');
            registrationDiv.style.visibility = "false";
            let loginDiv = document.getElementById('login-container');
            let messageDiv = document.getElementById('message-container');
        
            messageDiv.style.display = "none";
            //ready functions
            $(document).ready(function(){

                //button used for testing mailer.php without cron job
                //will execute mailer.php
                let mailerButton = document.getElementById('mailer');
                setVisible(mailerButton, false);

                //if user clicks on sign-up button
                $("#create").on("click", function(){
                    //sign up button listener
                    let username = $("#username").val();
                    let password = $("#password").val();
                    //check password on client side first
                    let validPwd = validatePassword(password);
                    //if password is not valid
                    if(!validPwd){
                        const errMsg = "Error: password is not valid";
                        displayMessageFor(3000,"account-create",errMsg);
                        return;
                    }
                    //random between 100,000 and 999,999
                    let salt = Math.round(Math.random()* 1000000);
                    let saltStr = salt.toString();
                    if(saltStr.length != 6){
                        //get the missed diffence of digits
                        let difference = Math.abs(saltStr.length - 6);
                        let carryOver = '';
                        //we have too little, just add 0's to fill to make 6 length
                        for(let i = 0; i < difference; i++)
                            carryOver += "0";
                        //append carryOver to the saltstr
                        saltStr += carryOver;
                        salt = parseInt(saltStr);

                    }
                    //salt = parseInt(saltStr); 
                    console.log("Salt: "+ salt);
                    //salt the password for transmission
                    password = salt+password;
                    //let saltedInput = salt+password;
                    //console.log("Salted Input: "+ saltedInput);
                    //we know its valid password from here
                    $.ajax(
                        {
                            url: "registration.php",
                            method: 'POST',
                            data:{
                                login: 0,
                                username: username,
                                password: password, 
                            },
                            success: function(response){
                                displayMessageFor(3000, 'account-create', response);
                            },
                            dataType: 'text'
                        }
                    );
                });
                 
                //if user clicks on login button
                $("#login").on("click", function(){
                    //variables that we will be sending to server or login.php
                    let username = $("#lusername").val();
                    let password = $("#lpassword").val();
                    //check password on client side first
                    let validPwd = validatePassword(password);
                    //if password is not valid
                    if(!validPwd){
                        const errMsg = "Error: incorrect password or username combination";
                        displayMessageFor(3000,"logInResponse",errMsg);
                        return;
                    }
                    //we know the user entered password field is valid
                    let userID;
                    $.ajax(
                        {
                            url: "lab4.php",
                            method: 'POST',
                            data:{
                                login: 1,
                                username: username,
                                password: password,
                                userID: "",
                                formNum: 1
                            },
                            success: function(response){
                                let logInElem = "logInResponse";
                                let incorrectRes = 'Invalid Username and Password';
                                //if did not succesfully login, display error message
                                if(response == incorrectRes)
                                    displayMessageFor(3000,logInElem,response);
                                //we know we got a succesfull match, display login divisions
                                //I will hide elements, however the page is not refreshing
                                else
                                    displayLogIn(response);
                            },
                            dataType: 'text'
                        }
                    );
                });

                //if the user clicks on schedule button
                $("#schedule").on("click", function(){
                    //variables that we will be sending to the server for schedule message operation
                    let email = $("#email").val();
                    let message = $("#message").val();
                    //time pickers...grab element 1 by 1
                    let date = $("#date").val();
                    let hour = $("#hourPicker").val();
                    let minute = $("#minutePicker").val();
                    let amPm = $("#am-pmPicker").val();
                    //if its PM, we need to add 12 hours to the value
                    if(amPm == 'PM'){
                        //parse it to int
                        hour = parseInt(hour);
                        //incrememt hour by 12
                        hour += 12;
                        //convert back to string
                        hour = hour.toString();
                    }
                    //concatenate string into SQL dateTime string
                    timeStamp = date + " " + hour + ":" +  minute  + ":" + "00";
                    console.log(typeof(minute));
                    console.log(timeStamp);
                    $.ajax(
                        {
                            url: "schedule.php",
                            method: 'POST',
                            data:{
                                login: 1,
                                'email': email,
                                'message': message,
                                'timeStamp': timeStamp,
                                formNum: 2
                            },
                            success: function(response){
                                $("#displayMessage").html(response);
                                setTimeout(()=>{
                                    $("#displayMessage").html("");
                                },1500);
                                console.log('success');
                            },
                            dataType: 'text'
                        }
                    );
                });

                //send emails...used for testing only
                $("#mailer").on("click", function(){
                    $.ajax(
                        {
                            url: "mailer.php",
                            method: 'POST',
                            data:{
                                login: 1,
                            },
                            success: function(response){
                                //$("#displayMessage").html(response);
                                console.log('success');
                            },
                            dataType: 'text'
                        }
                    );
                });
            });

            //validates password on client side before sending
            function validatePassword(password){
                var min = 8;
                var max = 30;
                var PWD_REGEX = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,30}/;
                //we check to see if size is greater than 8, smaller than 30, and 
                if(password.length < min || password.length > max)
                    return false;
                //check regular expression
                if(!PWD_REGEX.test(password))
                    return false;
                //we know its good from here
                return true;
            }
        
            //will handle organization of div elements
            function displayLogIn(logInMsg){
                //all error checking was done, we will just login
                let logInElem = "logInResponse";
                let datePicker = document.getElementById('date');
                //todays date
                let today = new Date();
                let day = today.getDate();
                let month = today.getMonth() + 1;
                let year = today.getFullYear();
                if(day.toString().length < 2)
                    day = "0" + day;
                datePicker.value = year + "-" + month + "-" + day;
                console.log(datePicker.value);
                //$("#logInResponse").html(response);
                displayMessage(logInElem,logInMsg);
                //$(logInElem).html(logInMsg);
                setVisible(messageDiv ,true);
                
                //setVisible(registrationDiv, false);
                //setVisible(loginDiv, false);
                
                //set default date to todays date
                //display spin tags
                generateSpinTags("hour", 12);
                generateSpinTags("minute", 2);
            }

            function displayMessage(element, message){
                let domElement = '#' + element;
                $(domElement).html(message);
            }

            //displays message for a certain amount of seconds
            //takes id of message box
            function displayMessageFor(time, element, message){
                let domElement = "#" + element;
                if(time <= 0) 
                    return;
                //time is valid...continue
                $(domElement).html(message);
                setTimeout(()=>{
                    $(domElement).html("");
                },time);
            }
            function pageStart(){
                messageDiv.style.display = "none";
            }

            function setVisible(element, value){
                if(element == null) return;
                if(value)
                    element.style.display = 'block';
                else
                    element.style.display = 'none';
            }

            //function used to generate a certain amount of tags for spinners
            function generateSpinTags(type, amount){
                let spinner = document.getElementById(type + 'Picker');
                switch(type){
                    case "hour":
                        for(let i = 0; i < amount; i++){
                            let option = document.createElement('option');
                            option.id = type + (i+1);
                            option.innerHTML = i + 1;
                            //append child
                            spinner.appendChild(option);   
                        }
                        break;

                    case "minute":
                        for(let i = 0; i < amount; i++){
                            let option = document.createElement('option');
                            option.id = type + (i+1);
                            if(i == 0){
                                option.innerHTML = "00";
                                option.value = "00";
                            }else{
                                option.innerHTML = "30";
                                option.value = "30";
                            }
                            spinner.appendChild(option);
                        }
                        break;
                }
            }
        </script>
</body>
</html>