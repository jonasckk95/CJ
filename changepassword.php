

<?php
include_once'connectdb.php';

session_start();

if($_SESSION['loginid']==""){
    
    
    header('location:index.php');
}


if($_SESSION['role']=="Admin"){

include_once'header.php';
}else{
    
  include_once'headeruser.php';  
}

if(isset($_POST['btnupdate'])){

    $oldpassword_txt=$_POST['txtoldpass'];
    $newpassword_txt=$_POST['txtnewpass'];
    $confpassword_txt=$_POST['txtconfpass'];
 
    $loginid=$_SESSION['userid'];
    
    $select=$pdo->prepare("select * from tbl_user where userid='$loginid'");
    
    $select->execute();
    $row=$select->fetch(PDO::FETCH_ASSOC);
    
    $userid_db= $row['userid'];
    $password_db= $row['password'];
    
    if($oldpassword_txt==$password_db){

        if($newpassword_txt==$confpassword_txt){

            $update=$pdo->prepare("update tbl_user set password=:pass where userid=:loginid");
            $update->bindParam(':pass',$confpassword_txt);
            $update->bindParam(':loginid',$loginid);
         
            if($update->execute()){
        
                echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Success",
  text: "Your Password Is Updated",
  icon: "success",
  button: "Ok",
});
});
</script>';
            }
            else{
                echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Error !!",
  text: "Query Fail",
  icon: "error",
  button: "Ok",
});
});
</script>';
            }
        }
        else{
         
         echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Error",
  text: "Your New Password And Confirm Password Is Not Matched",
  icon: "warning",
  button: "Ok",
});
});
</script>';
        }
    }
    else{
        echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Error",
  text: "Your Password Is Wrong",
  icon: "warning",
  button: "Ok",
});
});
</script>';
    }
}
?>

  <div class="content-wrapper">

    <section class="content-header">
      <h1>Change Password</h1>
    </section>

      <section class="content container-fluid">
          <div class="box box-warning">

              <form role="form" action="" method="post">
                  <div class="box-body">
                      <div class="form-group">
                          <label for="exampleInputPassword1">Old Password</label>
                          <input type="text" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtoldpass" required>
                      </div>
                      <div class="form-group">
                          <label for="exampleInputPassword1">New Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtnewpass" required>
                      </div>
                
                      <div class="form-group">
                          <label for="exampleInputPassword1">Confirm Password</label>
                          <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password" name="txtconfpass" required>
                      </div>
                  </div>
                  <div class="box-footer">
                      <button type="submit" class="btn btn-primary" name="btnupdate">Update</button>
                  </div>
              </form>
          </div>
      </section>
  </div>

  <?php

include_once'footer.php';

?>