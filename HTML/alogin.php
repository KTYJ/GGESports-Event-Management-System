<?php
    include ('toolman.php');
    require_once('sqlcon.php');
    session_start();
    if(isset($_SESSION['user_id'])&&isset($_SESSION['user_name'])){
        #normal user is logged in, log him out
        header("Location: logout.php");
    }
    else if(isset($_SESSION['admin_id'])){
        header("Location:adminh.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <title>SuperUser Login</title>
    
    <script src="../JAVAs/jquery-1.9.1.js">//Modify the <script> element to link to the local jQuery library file.</script>
    <script>
    $(document).ready(function() {
        $('#togglePassword').click(function() {
            const password = $('#pass');
            const type = password.attr('type') === 'password' ? 'text' : 'password';
            password.attr('type', type);

            $(this).toggleClass('bi-eye');
        });
    });
    </script>
    
    <style>
        body{
            margin:0;
            padding: 0;
            font-family: Tahoma, Geneva, Verdana, sans-serif;
            height: 100%;
            scrollbar-width: none;
            background: url(../Media/adminbg.jpg) repeat fixed;
        }

        body::-webkit-scrollbar{
            display: none;
        }

        p.error{
            color: red;
            font-weight: bold;
            animation: shake 0.3s;
            animation-iteration-count: 3;
        }
        @keyframes shake {
            0% { transform: translateX(0); color: red;}
            25% { transform: translateX(5px); color: white;}
            50% { transform: translateX(-5px); color: red;}
            75% { transform: translateX(5px); color: white;}
            100% { transform: translateX(0); color: red;}
        }
        .center{
            position: absolute;
            top: 50%;
            left: 50%;
            translate: -50% -50%;
            width: 500px;
            background: white;
        }

        .center h3{
            background-color: rgb(27, 91, 89);
            font-weight:90;
            font-size: 30px;
            color: white;
            text-align: center;
            margin: 0;
            padding: 50px 20px;
            border-bottom: 1px solid transparent;
            text-decoration: underline;
        }

        .center form{
            box-sizing: border-box;
            margin-top: 50px;
            padding: 0px 25px 30px 25px;
        }

        .txtfield{
            position: relative;
            border-bottom: 1.8px solid rgb(178, 178, 178);
            margin: 20px auto;
            width: 80%;
        }

        .txtfield input{
            width: 100%;
            height: 40px;
            font-size: 16px;
            border: none;
            background: none;
            outline: none;
            
        }
        .txtfield label{
            position: absolute;
            top: 50%;
            color: rgb(205, 203, 203);
            translate: 0 -40% ;
            transition: .5s all;
        }

        .txtfield i{
            position: absolute;
            cursor: pointer;
            margin: -30px 330px;
        }

        .txtfield span::before{
            content: '';
            position: absolute;
            top: 40px;
            left: 0px;
            width: 0%;
            height: 2.2px;
            background-color: rgb(66, 108, 147);
            transition: .5s all;
            translate: 0px 0.9px;
        }

        .txtfield input:focus ~ label,
        .txtfield input:not(:placeholder-shown) ~ label{
            top: -4px ;
            color: rgb(66, 108, 147);
        }
        .txtfield input:focus ~ span::before,
        .txtfield input:not(:placeholder-shown) ~ span::before{
            width: 100%;
        }

        .login input{
            background-color: rgb(2, 87, 87);
            border: none;
            border-radius: 10px;
            width: 100px;
            margin: auto;
            padding: 10px 30px;
            transition: 0.5s all;
            color: white;
        }

        .login input:hover{
            background-color: rgb(7, 179, 179);
        }

        .pass_txt a{text-decoration:none ;color: black; transition: 0.2s all;}
        .pass_txt a:hover{color: rgb(14, 152, 226); }
    </style>
</head>
<body>
    <div class="center">
        <h3>GGE SUPERUSER PORTAL</h3>
        <?php
            if(isset($_POST['id']) && isset($_POST['psw'])){
                #get variables after login attempt
                $id = validate($_POST['id']);
                $psw = validate($_POST['psw']); //Prevent hacks                    

                $spsw = sha1($psw);
                $sql = "SELECT * FROM admin WHERE adminID='$id' AND password='$spsw' ";
                $result = mysqli_query($con,$sql);

                if(mysqli_num_rows($result) === 1){
                    $row = mysqli_fetch_assoc($result);
                    if($row['adminId'] === $id && $row['password'] === $spsw){
                        $_SESSION['admin_id'] = $row['adminId'];
                        $result -> free_result();
                        $con -> close();
                        echo '<script>window.location="adminh.php"</script>'; //Redirect withou php
                    }
                    else{
                        $error = 'Incorrect ID or Password. ';
                    }
                }
                //When one or two fields are empty
                else if(empty($id) || empty($psw)){
                    $error = 'Please fill in ALL FIELDS.';
                }
                else{
                    $error = 'Incorrect ID or Password. ';
                }

                if(isset($error)){
                    printf("<p align='center' class='error'>%s<p>",$error);
                }
            }
            
        ?>
        <form method="post">
            <div class="txtfield">
                <input type="text" id="id" name="id" placeholder=" ">
                <span></span>
                <label for="id">user id</label>
            </div>
            <div class="txtfield">
                <input type="password" name="psw" id="pass" placeholder=" ">
                <i class="bi bi-eye-slash" id="togglePassword"></i>
                <span></span>
                <label for="pass">password</label>
            </div>
            <div class="login" style="width:100%;text-align: center;margin: 30px 0px 20px 0px;">
                <input type="submit" name="" value="login">
            </div>
            <div class="pass_txt" align="center" style="font-size: 11px;">
                <!--Please CONTACT YOUR <b>SUPER ADMIN</b> if you do not have access/forgot your credentials.
                <br/>-->
                If you are not an admin, <a href="Homepage.php?menu=login"><b>click here to return to user login.</b></a>
            </div>
        </form>
    </div>
   
</body>
</html>