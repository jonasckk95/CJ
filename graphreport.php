


<?php
include_once'connectdb.php';
error_reporting(0);
session_start();
if($_SESSION['loginid']=="" OR $_SESSION['role']=="User"){
    
    header('location:index.php');
}

include_once'header.php';

?>
  <div class="content-wrapper">

    <section class="content-header">
      <h1>Graph Report</h1>

    </section>

    <section class="content container-fluid">

        <div class="box box-warning">
            <form  action="" method="post" name="">

                <div class="box-header with-border">
                    <h3 class="box-title">From : <?php echo $_POST['date_1']?> -- To : <?php echo $_POST['date_2']?></h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>

                                <input type="text" class="form-control pull-right" id="datepicker1" name="date_1"  data-date-format="yyyy-mm-dd" >
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker2" name="date_2"  data-date-format="yyyy-mm-dd" >
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div align="left">
                                <input type="submit" name="btndatefilter" value="Filter By Date" class="btn btn-success">
                            </div>

                        </div>
                    </div>
           
                    <br><br>

                    <?php
                    $select=$pdo->prepare("select order_date, sum(total) as price from tbl_invoice  where order_date between :fromdate AND :todate group by order_date");
                    $select->bindParam(':fromdate',$_POST['date_1']);
                    $select->bindParam(':todate',$_POST['date_2']);

                    $select->execute();

                    $total=[];
                    $date=[];

                    while($row=$select->fetch(PDO::FETCH_ASSOC)  ){

                        extract($row);
                        $total[]=$price;
                        $date[]=$order_date;

                    }

                    ?>

                    <div class="chart">
                        <canvas id="myChart" style="height:250px"></canvas>
                    </div>
                  
                  
                    <?php
                    $select=$pdo->prepare("select product_name, sum(qty) as q from tbl_invoice_details  where order_date between :fromdate AND :todate group by product_id");
                    $select->bindParam(':fromdate',$_POST['date_1']);
                    $select->bindParam(':todate',$_POST['date_2']);
                    $select->execute();

                    $pname=[];
                    $qty=[];
                    $total;
                    while($row=$select->fetch(PDO::FETCH_ASSOC)  ){
                        extract($row);
                        $pname[]=$product_name;
                        $qty[]=$q;
                    }

                    ?>

                    <div class="chart">
                        <canvas id="bestsellingproduct" style="height:250px"></canvas>
                    </div>

                </div>
            </form>
        </div>
    </section>
  </div>
  
  
  <script>
var ctx = document.getElementById('myChart').getContext('2d');
var chart = new Chart(ctx, {
    // can use bar or line
    type: 'line',
    data: {
        labels: <?php echo json_encode($date);?>,
        datasets: [{
            label: 'Total Earning',
            backgroundColor: 'rgb(255, 200, 90)',
            borderColor: 'rgb(255, 200, 90)',
            data:<?php echo json_encode($total);?>
        }]
    },
});
</script>
 

<script>
var ctx = document.getElementById('bestsellingproduct').getContext('2d');
var chart = new Chart(ctx, {
    // bar is better
    type: 'bar',
    data: {
        labels: <?php echo json_encode($pname);?>,
        datasets: [{
            label: 'Total Qunatity',
             backgroundColor: 'rgb(94, 200, 105)',
            borderColor: 'rgb(94, 102, 105)',
            data:<?php echo json_encode($qty);?>
        }]
    },
});
</script>
  

<script>
    $('#datepicker1').datepicker({autoclose: true});
    $('#datepicker2').datepicker({autoclose: true});
</script>
  

  <?php

include_once'footer.php';

?>






