<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);

// print_r($user_data);

# check post value
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # get the form params
    $username = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($username) && !empty($password) && !is_numeric($password)) {
        # if the login they entered matches the username logged in, then they match.
        if ($username == $user_data['user_name']) {
            echo "The entered username and seesion username match!";
            echo $_SESSION['user_id'];
            $query = "UPDATE users SET password= '$password' WHERE user_id = '{$_SESSION['user_id']}'";
            $result = mysqli_query($con, $query);
            echo '<script type="text/JavaScript"> 
             alert("your password was changed, please login again.");
             </script>';
        } else {
            echo '<script type="text/JavaScript"> 
            alert("This username does not match the current logged in user. You can only change your own password!");
            </script>';
        }
    } else {
        echo '<script type="text/JavaScript"> 
        alert("Fields cant be blank!!");
        </script>';
    }



    /*
    echo $username;
    echo " ";
    echo $password;
    */
}
?>


<!DOCTYPE html>
<html>

<head>
    <title>Change password</title>
</head>

<body>

    <style type="text/css">
        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }

        #button {

            padding: 10px;
            width: 100px;
            color: white;
            background-color: lightblue;
            border: none;
        }

        #box {

            background-color: grey;
            margin: auto;
            width: 300px;
            padding: 20px;
        }
    </style>

    <div id="box">

        <form method="post">
            <div style="font-size: 20px;margin: 10px;color: white;">Change password</div>

            <input id="text" placeholder="username here" type="text" name="user_name"><br><br>
            <input id="text" placeholder= "new password here" type="password" name="password"><br><br>

            <input id="button" type="submit" value="Change pwd"><br><br>

            <a href="login.php">Click to Login</a><br><br>
        </form>
    </div>
</body>

</html>