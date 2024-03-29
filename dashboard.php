<?php

include_once'connectdb.php';
session_start();

if($_SESSION['loginid']=="" OR $_SESSION['role']=="User"){


    header('location:dashboard.php');
}



$select = $pdo->prepare("select sum(total) as t , count(invoice_id) as inv from tbl_invoice");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);

$total_order=$row->inv;

$net_total=$row->t;







$select=$pdo->prepare("select order_date, total from tbl_invoice  group by order_date LIMIT 30");


$select->execute();

$ttl=[];
$date=[];

while($row=$select->fetch(PDO::FETCH_ASSOC)  ){

    extract($row);

    $ttl[]=$total;
    $date[]=$order_date;


}



include_once'header.php';

?>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Admin Dashboard
            </h1>
        </section>

        <section class="content container-fluid">



            <div class="box-body">

                <div class="row">
                    <div class="col-lg-3">
                        <div class="small-box bg-olive">
                            <div class="inner">
                                <h3><?php echo $total_order;?></h3>

                                <p>Total Orders</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-cart"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="small-box bg-aqua">
                            <div class="inner">
                                <h3><?php echo "$".number_format($net_total,2);?><sup style="font-size: 20px"></sup></h3>

                                <p>Total Revenue</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>


                    <?php
                    $select = $pdo->prepare("select count(pname) as p from tbl_product");
                    $select->execute();
                    $row=$select->fetch(PDO::FETCH_OBJ);

                    $total_product=$row->p;


                    ?>


                    <div class="col-lg-3">
                        <div class="small-box bg-green">
                            <div class="inner">
                                <h3><?php echo $total_product;?></h3>

                                <p>Total Product</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pizza"></i>
                            </div>
                        </div>
                    </div>




                    <?php
                    $select = $pdo->prepare("select count(category) as cate from tbl_category");
                    $select->execute();
                    $row=$select->fetch(PDO::FETCH_OBJ);

                    $total_category=$row->cate;


                    ?>




                    <div class="col-lg-3">
                        <div class="small-box bg-teal">
                            <div class="inner">
                                <h3><?php echo $total_category;?></h3>

                                <p>Total Category</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-pie-graph"></i>
                            </div>
                        </div>
                    </div>

                </div>


                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Earning By Date</h3>
                    </div>
                    <div class="box-body">
                        <div class="chart">
                            <canvas id="earningbydate" style="height:250px"></canvas>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Best Selling Product</h3>
                            </div>
                            <div class="box-body">
                                <table id="bestsellingproductlist" class="table">
                                    <thead>
                                    <tr>
                                        <th>Product ID</th>
                                        <th>Product Name</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                    </thead>

                                    <tbody>

                                    <?php
                                    $select=$pdo->prepare("select product_id,product_name,price,sum(qty) as q , sum(qty*price) as total from tbl_invoice_details group by product_id order by sum(qty) DESC LIMIT 15");
                                    $select->execute();

                                    while($row=$select->fetch(PDO::FETCH_OBJ)  ){
                                        echo'
    <tr>
    <td>'.$row->product_id.'</td>
    <td>'.$row->product_name.'</td>
    <td><span class="label label-info">'.$row->q.'</span></td>
    <td><span class="label label-success">'."$".$row->price.'</span></td>
     <td><span class="label label-danger">'."$".$row->total.'</span></td>
   
    
    
        </tr>
     ';
                                    }
                                    ?>
                                    </tbody>
                                </table>


                            </div>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title">Recent Orders</h3>
                            </div>

                            <div class="box-body">
                                <table id="orderlisttable" class="table">
                                    <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>CustomerName</th>
                                        <th>OrderDate</th>
                                        <th>Total</th>
                                        <th>Payment Type</th>
                                    </tr>
                                    </thead>



                                    <tbody>

                                    <?php
                                    $select=$pdo->prepare("select * from tbl_invoice  order by invoice_id desc LIMIT 15");
                                    $select->execute();

                                    while($row=$select->fetch(PDO::FETCH_OBJ)  ){

                                        echo'
    <tr>
    <td><a href="editorder.php?id='.$row->invoice_id.'">'.$row->invoice_id.'</a></td>
    <td>'.$row->customer_name.'</td>
    <td>'.$row->order_date.'</td>
    <td><span class="label label-danger">'."$".$row->total.'</span></td>';


                                        if($row->payment_type=="Cash"){
                                            echo'<td><span class="label label-warning">'.$row->payment_type.'</span></td>';

                                        }elseif($row->payment_type=="Card"){
                                            echo'<td><span class="label label-success">'.$row->payment_type.'</span></td>';
                                        }else{
                                            echo'<td><span class="label label-primary">'.$row->payment_type.'</span></td>';
                                        }

                                    }
                                    ?>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>
    </div>






    <script>
        var ctx = document.getElementById('earningbydate').getContext('2d');
        var chart = new Chart(ctx, {
            // can use line or bar
            type: 'line',
            data: {
                labels: <?php echo json_encode($date);?>,
                datasets: [{
                    label: 'Total Earning',
                    backgroundColor: 'rgb(255, 200, 90)',
                    borderColor: 'rgb(255, 200, 90)',
                    data:<?php echo json_encode($ttl);?>
                }]
            },
        });
    </script>


        <script>
      $(document).ready( function () {
        $('#bestsellingproductlist').DataTable({
             "order":[[0,"asc"]]
         });
    } );


    </script>
      <script>
      $(document).ready( function () {
        $('#orderlisttable').DataTable({
            "order":[[0,"desc"]]
         });
    } );
    </script>




<?php

include_once'footer.php';

?>