<?php
include_once'connectdb.php';

session_start();

if($_SESSION['loginid']=="" OR $_SESSION['role']=="User"){

    header('location:index.php');
}

include_once'header.php';


error_reporting(0);

$id=$_GET['id'];

$delete=$pdo->prepare("delete from tbl_user where userid=".$id);

if($delete->execute()){
    echo'<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Deleted!",
  text: "Account is deleted",
  icon: "success",
  button: "Ok",
});
});
</script>';
}


if(isset($_POST['btnsave'])){
    $username=$_POST['txtname'];
    $userloginid=$_POST['txtloginid'];
    $password=$_POST['txtpassword'];
    $userrole=$_POST['txtselect_option'];

    if(isset($_POST['txtloginid'])) {
        $select = $pdo->prepare("select loginid from tbl_user where loginid='$userloginid'  ");
        $select->execute();

        if ($select->rowCount() > 0) {

            echo '<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Warning!",
  text: "loginid Already Exist : Please try from diffrent loginid !!",
  icon: "warning",
  button: "Ok",
});
});
</script>';
        }

        else {
            $insert = $pdo->prepare("insert into tbl_user(username,loginid,password,role) values(:name,:loginid,:pass,:role)");
            $insert->bindParam(':name', $username);
            $insert->bindParam(':loginid', $userloginid);
            $insert->bindParam(':pass', $password);
            $insert->bindParam(':role', $userrole);

            if ($insert->execute()) {
                echo '<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Good Job!",
  text: "Your Registration is Successfull",
  icon: "success",
  button: "Ok",
});
});
</script>';
            }
            else {
                echo '<script type="text/javascript">
jQuery(function validation(){
swal({
  title: "Error!",
  text: "Registration Fail !!!",
  icon: "error",
  button: "Ok",
});
});
</script>';
            }
        }
    }
}
?>

    <div class="content-wrapper">

        <section class="content-header">
            <h1>Manage Accounts</h1>
        </section>

        <section class="content container-fluid">

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">New accounts</h3>
                </div>
                <form role="form" action="" method="post">
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label >Name</label>
                                <input type="text" class="form-control" name="txtname" placeholder="Enter Name" required>
                            </div>

                            <div class="form-group">
                                <label >Login Id</label>
                                <input type="text" class="form-control" name="txtloginid" placeholder="Enter Login Id" required>
                            </div>

                            <div class="form-group">
                                <label >Password</label>
                                <input type="password" class="form-control" name="txtpassword" placeholder="Password" required>
                            </div>

                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control" name="txtselect_option" required>
                                    <option value="" disabled selected>Select role</option>
                                    <option>User</option>
                                    <option>Admin</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" name="btnsave">Save</button>
                        </div>

                        <div class="col-md-8">

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NAME</th>
                                    <th>LOGIN ID</th>
                                    <th>PASSWORD</th>
                                    <th>ROLE</th>
                                    <th>DELETE</th>
                                </tr>
                                </thead>
                                <tbody>

                                <?php
                                $select=$pdo->prepare("select * from tbl_user  order by userid desc");
                                $select->execute();
                                while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                                    echo'
    <tr>
    <td>'.$row->userid.'</td>
    <td>'.$row->username.'</td>
    <td>'.$row->loginid.'</td>
    <td>'.$row->password.'</td>
    <td>'.$row->role.'</td>
    <td>
<a href="manageuser.php?id='.$row->userid.'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash"  title="delete"></span></a>   
    </td>
    </tr>
     ';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                    </div>
                </form>
            </div>
        </section>
    </div>

<?php

include_once'footer.php';

?>