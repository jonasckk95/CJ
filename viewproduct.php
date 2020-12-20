<?php

include_once'connectdb.php';

session_start();

if($_SESSION['loginid']=="" OR $_SESSION['role']==""){


    header('location:index.php');
}



if($_SESSION['role']=="Admin"){


    include_once'header.php';
}else{

    include_once'headeruser.php';
}

?>

  <div class="content-wrapper">

      <section class="content-header">
      <h1>View Product</h1>
      </section>


      <section class="content container-fluid">

          <div class="box box-warning">

              <div class="box-body">

                  <?php
                  $id=$_GET['id'];
                  $select=$pdo->prepare("select * from tbl_product where pid=$id");
                  $select->execute();
                  while($row=$select->fetch(PDO::FETCH_OBJ)){
                      echo'
<div class="col-md-6">

<ul class="list-group">
<p class="list-group-item list-group-item-success"><b>Product Detail</b></p>

<li class="list-group-item"><b>ID</b> <span class="badge">'.$row->pid.'</span></li>
<li class="list-group-item"><b>Product Name</b> <span class="label label-info pull-right">'.$row->pname.'</span></li>
<li class="list-group-item"><b>Category</b> <span class="label label-primary pull-right">'.$row->pcategory.'</span></li>
<li class="list-group-item"><b>Purchase price</b> <span class="label label-warning pull-right">'.$row->purchaseprice.'</span></li>
<li class="list-group-item"><b>Sale Price</b> <span class="label label-warning pull-right">'.$row->saleprice.'</span></li>
<li class="list-group-item"><b>Product Profit </b><span class="label label-success pull-right">'.($row->saleprice-$row->purchaseprice).'</span></li>
<li class="list-group-item"><b>Stock </b><span class="label label-danger pull-right">'.$row->pstock.'</span></li>
<li class="list-group-item"><b>Description:- </b><span class="">'.$row->pdescription.'</span></li>
  
</ul>

</div>

<div class="col-md-6">

<ul class="list-group">
<p class="list-group-item list-group-item-success"><b>Product image</b></p>

<img src = "productimages/'.$row->pimage.'" class="img-responsive"/>
  
</ul>

</div>

';    
    

}                  
                  
?>
                  
</div>
          </div>

      </section>
  </div>


  <?php

include_once'footer.php';

?>