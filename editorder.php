<?php

include_once'connectdb.php';

session_start();

if($_SESSION['loginid']=="" OR $_SESSION['role']==""){
    
    
    header('location:index.php');
}

function fill_product($pdo,$pid){
    $output='';
    $select=$pdo->prepare("select * from tbl_product order by pname asc");
    $select->execute();
    $result=$select->fetchAll();

    foreach($result as $row){
        $output.='<option value="'.$row["pid"].'"';
        if($pid==$row['pid']){
            $output.='selected';
        }
        $output.='>'.$row["pname"].'</option>';
    }
    return $output;
}

$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id =$id");
$select->execute();

$row=$select->fetch(PDO::FETCH_ASSOC);

$customer_name=$row['customer_name'];
$order_date=date('Y-m-d',strtotime($row['order_date']));
$subtotal=$row["subtotal"];
$discount=$row['discount'];
$total=$row['total'];
$paid=$row['paid'];
$due=$row['due'];
$payment_type=$row['payment_type'];

$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id =$id");
$select->execute();

$row_invoice_details=$select->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['btnupdateorder'])){
    


// 1) Get values from text feilds and from array in variables.
    $txt_customer_name=$_POST['txtcustomer'];
    $txt_order_date=date('Y-m-d',strtotime($_POST['orderdate']));
    $txt_subtotal=$_POST["txtsubtotal"];
    $txt_discount=$_POST['txtdiscount'];
    $txt_total=$_POST['txttotal'];
    $txt_paid=$_POST['txtpaid'];
    $txt_due=$_POST['txtdue'];
    $txt_payment_type=$_POST['rb'];

    $arr_productid=$_POST['productid'];
    $arr_productname=$_POST['productname'];
    $arr_stock=$_POST['stock'];
    $arr_qty=$_POST['qty'];
    $arr_price=$_POST['price'];
    $arr_total=$_POST['total'];
    
    foreach($row_invoice_details as $item_invoice_details){
        $updateproduct=$pdo->prepare("update tbl_product set pstock=pstock+".$item_invoice_details['qty']." where pid='".$item_invoice_details['product_id']."'");
        $updateproduct->execute();
    }

    $delete_invoice_details=$pdo->prepare("delete from tbl_invoice_details where invoice_id=$id");
    $delete_invoice_details->execute();
    
    
    $update_invoice=$pdo->prepare("update tbl_invoice set customer_name=:cust,order_date=:orderdate,subtotal=:stotal,discount=:disc,total=:total,paid=:paid,due=:due,payment_type=:ptype where invoice_id=$id");
    $update_invoice->bindParam(':cust',$txt_customer_name);
    $update_invoice->bindParam(':orderdate',$txt_order_date);
    $update_invoice->bindParam(':stotal' ,$txt_subtotal);
    $update_invoice->bindParam(':disc',$txt_discount);
    $update_invoice->bindParam(':total',$txt_total);
    $update_invoice->bindParam(':paid',$txt_paid);
    $update_invoice->bindParam(':due',$txt_due);
    $update_invoice->bindParam(':ptype',$txt_payment_type);
   
    $update_invoice->execute();
    
    $invoice_id=$pdo->lastInsertId();
    if($invoice_id!=null){
    
        for($i=0 ; $i<count($arr_productid) ; $i++){

            $selectpdt=$pdo->prepare("select * from tbl_product where pid='".$arr_productid[$i]."'");
            $selectpdt->execute();
            while($rowpdt=$selectpdt->fetch(PDO::FETCH_OBJ)){
    
                $db_stock[$i]=$rowpdt->pstock;
                $rem_qty = $db_stock[$i]-$arr_qty[$i];
    
                if($rem_qty<0){
                    return"Order Is Not Complete";
                }else{
                    $update=$pdo->prepare("update tbl_product SET pstock ='$rem_qty' where pid='".$arr_productid[$i]."'");
                    $update->execute();
                }
            }

            $insert=$pdo->prepare("insert into tbl_invoice_details(invoice_id,product_id,product_name,qty,price,order_date) values(:invid,:pid,:pname,:qty,:price,:orderdate)");
            $insert->bindParam(':invid',$id);
            $insert->bindParam(':pid', $arr_productid[$i]);
            $insert->bindParam(':pname',$arr_productname[$i]);
            $insert->bindParam(':qty',$arr_qty[$i]);
            $insert->bindParam(':price',$arr_price[$i]);
            $insert->bindParam(':orderdate',$txt_order_date);
            $insert->execute();
        }
        
        header('location:orderlist.php');
    }
}
    

if($_SESSION['role']=="Admin"){
    include_once'header.php';
}else{
    include_once'headeruser.php';
}

?>

<div class="content-wrapper">

    <section class="content-header">
        <h1>Edit Order</h1>
    </section>

    <section class="content container-fluid">
        <div class="box box-warning">
            <form action="" method="post" name="">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Order</h3>
                </div>

                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Customer Name</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>

                                <input type="text" class="form-control" name="txtcustomer" value="<?php echo $customer_name;?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker" name="orderdate" value="<?php echo $order_date;?>" >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-body">
                    <div class="col-md-12">
                        <div style="overflow-x:auto;" >
                            <table class="table table-bordered" id="producttable"  >

                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Search Product</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Enter Quantity</th>
                                    <th>Total</th>
                                    <th>
                                        <button type="button" name="add" class="btn btn-info btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button>
                                    </th>
                                </tr>
                                </thead>

                                <?php
                                foreach($row_invoice_details as $item_invoice_details){
                                    $select=$pdo->prepare("select * from tbl_product where pid ='{$item_invoice_details['product_id']}'");
                                    $select->execute();
                                    $row_product=$select->fetch(PDO::FETCH_ASSOC);
                                    ?>
                                    <tr>

                                        <?php
                                        echo'<td><input type="hidden" class="form-control pname" name="productname[]" value="'.$row_product['pname'].'" readonly></td>';
                                        echo'<td><select class="form-control productidedit" name="productid[]" style="width: 250px";><option value="">Select Option</option>'.fill_product($pdo,$item_invoice_details['product_id']).' </select></td>';
                                        echo'<td><input type="text" class="form-control stock" name="stock[]" value="'.$row_product['pstock'].'" readonly></td>';
                                        echo'<td><input type="text" class="form-control price" name="price[]" value="'.$row_product['saleprice'].'" readonly></td>';
                                        echo'<td><input type="number" min="1" class="form-control qty" name="qty[]" value="'.$item_invoice_details['qty'].'" ></td>';
                                        echo'<td><input type="text" class="form-control total" name="total[]" value="'.$row_product['saleprice']*$item_invoice_details['qty'].'" readonly></td>';
                                        echo'<td><but ton type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td>';
          
                                        ?>
                                    </tr>
                                <?php } ?>
                            </table></div>
                    </div>
                </div>

                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>SubTotal</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>

                                <input type="text" class="form-control" value="<?php echo $subtotal?>" name="txtsubtotal" id="txtsubtotal" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Discount</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                 <input type="text" class="form-control" value="<?php echo $discount?>" name="txtdiscount" id="txtdiscount" required>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>

                                <input type="text" class="form-control" value="<?php echo $total?>" name="txttotal" id="txttotal" required readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Paid</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control" value="<?php echo $paid?>" name="txtpaid"  id="txtpaid" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Due</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-usd"></i>
                                </div>
                                <input type="text" class="form-control"  value="<?php echo $due?>" name="txtdue" id="txtdue" required readonly>
                            </div>
                        </div>

                        <label>Payment Method</label>
                        <div class="form-group">

                            <label>
<input type="radio" name="rb" class="minimal-red" value="Cash"<?php echo ($payment_type=='Cash')?'checked':''?>> CASH
                            </label>
                            <label>
<input type="radio" name="rb" class="minimal-red" value="Card"<?php echo ($payment_type=='Card')?'checked':''?> > CARD
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
                <div align="center">
                    <input type="submit" name="btnupdateorder" value="Update Order" class="btn btn-primary">
                </div>
                <hr>
            </form>
        </div>
    </section>
</div>

<script>

    $('#datepicker').datepicker({autoclose: true});
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
    })
    $(document).ready(function(){
        $('.productidedit').select2()
        $(".productidedit").on('change' , function(e){
            var productid = this.value;
            var tr=$(this).parent().parent();
            $.ajax({
                url:"getproduct.php",
                method:"get",
                data:{id:productid},
                success:function(data){

                    tr.find(".pname").val(data["pname"]);
                    tr.find(".stock").val(data["pstock"]);
                    tr.find(".price").val(data["saleprice"]);
                    tr.find(".qty").val(1);
                    tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val());
                    calculate(0,0);
                    $("#txtpaid").val("");
                }
            })
        })
        $(document).on('click','.btnadd',function(){
            var html='';
            html+='<tr>';
            html+='<td><input type="hidden" class="form-control pname" name="productname[]" readonly></td>';
            html+='<td><select class="form-control productid" name="productid[]" style="width: 250px";><option value="">Select Option</option><?php echo fill_product($pdo,''); ?> </select></td>';
            html+='<td><input type="text" class="form-control stock" name="stock[]" readonly></td>';
            html+='<td><input type="text" class="form-control price" name="price[]" readonly></td>';
            html+='<td><input type="number" min="1" class="form-control qty" name="qty[]" ></td>';
            html+='<td><input type="text" class="form-control total" name="total[]" readonly></td>';
            html+='<td><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button><center></td></center>';
        
            $('#producttable').append(html);
        
            $('.productid').select2()
            $(".productid").on('change' , function(e){
                var productid = this.value;
                var tr=$(this).parent().parent();
                $.ajax({
                    url:"getproduct.php",
                    method:"get",
                    data:{id:productid},
                    success:function(data){

                        tr.find(".pname").val(data["pname"]);
                        tr.find(".stock").val(data["pstock"]);
                        tr.find(".price").val(data["saleprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".total").val( tr.find(".qty").val() *  tr.find(".price").val());
                        calculate(0,0);
                        $("#txtpaid").val("");
                    }
                })
            })
        })
       
        $(document).on('click','.btnremove',function(){
            $(this).closest('tr').remove();
            calculate(0,0);
            $("#txtpaid").val("");
        })
        
        $("#producttable").delegate(".qty","keyup change" ,function(){
            var quantity = $(this);
            var tr=$(this).parent().parent();
            $("#txtpaid").val("");
            if((quantity.val()-0)>(tr.find(".stock").val()-0) ){
       
                swal("WARNING!","SORRY! This much of quantity is not available","warning");
        
                quantity.val(1);
                tr.find(".total").val(quantity.val() *  tr.find(".price").val());
                calculate(0,0);
            }else{
                tr.find(".total").val(quantity.val() *  tr.find(".price").val());
                calculate(0,0);
            }
        
        })
        function calculate(dis,paid){
         
            var subtotal=0;
            var discount = dis;
            var net_total=0;
            var paid_amt=paid;
            var due=0;
         
         
            $(".total").each(function(){
        
                subtotal = subtotal+($(this).val()*1);
            })
         

            net_total=subtotal;
            net_total=net_total-discount;
            due=net_total-paid_amt;
         
    
            $("#txtsubtotal").val(subtotal.toFixed(2));
            $("#txttotal").val(net_total.toFixed(2));
            $("#txtdiscount").val(discount);
            $("#txtdue").val(due.toFixed(2));
        }
        
        $("#txtdiscount").keyup(function(){
            var discount = $(this).val();
            calculate(discount,0);
        })

        $("#txtpaid").keyup(function(){
            var paid = $(this).val();
            var discount = $("#txtdiscount").val();
            calculate(discount,paid);
    
        })
        
    });

</script>


<?php

include_once'footer.php';

?>