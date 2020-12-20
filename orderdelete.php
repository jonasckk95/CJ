<?php

include_once'connectdb.php';




$id=$_POST['pidd'];


$sql="delete tbl_invoice , tbl_invoice_details FROM tbl_invoice INNER JOIN tbl_invoice_details ON tbl_invoice.invoice_id = tbl_invoice_details.invoice_id where tbl_invoice.invoice_id=$id";


$delete=$pdo->prepare($sql);


if($delete->execute()){
    
    
}else{
    
  echo'Error in Deleting';  
}






?>