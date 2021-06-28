<?php
session_start();

include("connection.php");
include("functions.php");

$user_data = check_login($con);


# check post value
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    # get the form params
    $username = $_POST['user_name'];
    $password = $_POST['password'];
    $newpass = $_POST['new_password'];
    // $existing_password = $_POST['current_pass'];

    if (!empty($username) && !empty($password)) {
        # if the login they entered matches the username logged in, then they match.
        if ($username == $user_data['user_name'] && $user_data['password'] == $password) {
            echo "The entered username and seesion username match!";
            echo $_SESSION['user_id'];
            $query = "UPDATE users SET password= '$newpass' WHERE user_id = '{$_SESSION['user_id']}'";
            $result = mysqli_query($con, $query);
            // wait for page to load and display modal by clicking

            // logout user 
            if (isset($_SESSION['user_id'])) {
                unset($_SESSION['user_id']);
            }

            // display modal window
            echo "<script type='text/JavaScript'>
            window.addEventListener('load', function () {
                document.querySelector('button[data-target=\"#myModal\"]').click()
              })
            </script>";

        } else {
            echo "
            <script type='text/JavaScript'>
            window.addEventListener('load', function () {
                document.querySelector('h4[class=\"modal-title\"]').innerText = 'Wrong password entered';
                document.querySelector('div[class=\"modal-body\"] > p').innerText  = \"The current password you entered is wrong.\";
                document.querySelector('button[data-target=\"#myModal\"]').click()
              })
            </script>";
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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

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

        button[data-target="#myModal"] {
            display: none;

        }
    </style>

    <div id="box">

        <form method="post">
            <div style="font-size: 20px;margin: 10px;color: white;">Change password</div>

            <input id="text" value="<?php echo $user_data['user_name']; ?>" placeholder="username here" readonly type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password" placeholder="existing pass">
            <input id="text" placeholder="new password here" type="password" name="new_password"><br><br>


            <input id="button" type="submit" value="Change pwd"><br><br>

            <a href="index.php">Click to access Dashboard</a><br><br>
        </form>
    </div>

    <div class="container">
        <!-- Trigger the modal with a button -->
        <button type="button" type="hidden" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Password change successful</h4>
                    </div>
                    <div class="modal-body">
                        <p>Your password was changed successfully, please login again.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type=“button”><a href="login.php">Login now!</a></button>
                    </div>
                </div>

            </div>
        </div>

    </div>

</body>

</html>
