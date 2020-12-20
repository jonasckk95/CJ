


<?php
include_once'connectdb.php';
session_start();
if(isset($_POST['btn_login'])){
    $userloginid = $_POST['txt_loginid'];
    $password =   $_POST['txt_password'];
    $select= $pdo->prepare("select * from tbl_user where loginid='$userloginid' AND  password='$password'");
    $select->execute();
    $row= $select->fetch(PDO::FETCH_ASSOC);
    if($row['loginid']==$userloginid AND $row['password']==$password AND $row['role']=="Admin" ){
        $_SESSION['userid']=$row['userid'];
        $_SESSION['username']=$row['username'];
        $_SESSION['loginid']=$row['loginid'];
        $_SESSION['role']=$row['role'];
        $message = 'success';
        header('refresh:2;dashboard.php');

    }else if($row['loginid']==$userloginid AND $row['password']==$password AND $row['role']=="User" ){
        $_SESSION['userid']=$row['userid'];
        $_SESSION['username']=$row['username'];
        $_SESSION['loginid']=$row['loginid'];
        $_SESSION['role']=$row['role'];

        $message = 'success';
        header('refresh:3;terminal.php');

    }else{
        $errormsg='error';


    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FarmApp | Login </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- jQuery 3 -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js"></script>
    <script src="bower_components/sweetalert/sweetalert.js"></script>



    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">


    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">

<div class="login-box">
    <div class="login-logo">
        <a href="index.php"><b>Farm</b>App</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign in</p>

        <form action="" method="post">

            <div class="form-group has-feedback">
                <input type="text" class="form-control" placeholder="Login id" name="txt_loginid" required>
                <i class="fa fa-id-card form-control-feedback"></i>

            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" placeholder="Password" name="txt_password"  required>
                <i class="fa fa-lock form-control-feedback"></i>
            </div>
            <div class="row">

                <div class="col-xs-4 pull-right">
                    <button type="submit" class="btn btn-primary btn-block btn-flat" name="btn_login">Login</button>
                </div>

            </div>


            <?php

            if(!empty($message)){

                echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Welcome!'.$_SESSION['username'].'",
  icon: "success",
  button: "Loading.....",
  });
 });
</script>';

            }else{}


            if(empty($errormsg)){

            }else{


                echo'<script type="text/javascript">
jQuery(function validation(){


swal({
  title: "LOGIN ID OR PASSWORD IS WRONG!",
  text: "Details Not Matched",
  icon: "error",
  button: "Ok",
});


});

</script>';

            }

            ?>

        </form>
    </div>
</div>


</body>
</html>





