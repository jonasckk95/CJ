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
            <h1>Product List</h1>
        </section>

        <section class="content container-fluid">
            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Product list</h3>
                </div>

                <div class="box-body">
                    <div style="overflow-x:auto;" >
                        <table id="producttable" class="table table-striped">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Product name</th>
                                <th>Category</th>
                                <th>Purchaseprice</th>
                                <th>Sale Price</th>
                                <th>Stock</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>View</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            $select=$pdo->prepare("select * from tbl_product  order by pid desc");
                            $select->execute();
                            while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                                echo'
<tr>
    <td>'.$row->pid.'</td>
    <td>'.$row->pname.'</td>
    <td>'.$row->pcategory.'</td>
    <td>'.$row->purchaseprice.'</td>
    <td>'.$row->saleprice.'</td>
    <td>'.$row->pstock.'</td>
    <td>'.$row->pdescription.'</td>
    <td><img src = "productimages/'.$row->pimage.'" class="img-rounded" width="40px" height="40px"/></td>
    <td>
        <a href="viewproduct.php?id='.$row->pid.'" class="btn btn-success" role="button"><span class="glyphicon glyphicon-eye-open"  style="color:#ffffff" data-toggle="tooltip"  title="View Product"></span></a>   
    </td>   
</tr>
     ';
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>



    <script>
        $(document).ready( function () {
            $('#producttable').DataTable({
                "order":[[0,"desc"]]
            });
        } );
    </script>


    <script>
        $(document).ready( function () {
            $('[data-toggle="tooltip"]').tooltip();
        } );
    </script>




<?php

include_once'footer.php';

?>