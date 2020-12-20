<?php

require('fpdf/fpdf.php');
include_once'connectdb.php';


$id=$_GET['id'];
$select=$pdo->prepare("select * from tbl_invoice where invoice_id=$id");
$select->execute();
$row=$select->fetch(PDO::FETCH_OBJ);



$pdf = new FPDF('P','mm',array(80,140));


$pdf->AddPage();


$pdf->SetFont('Arial','B',16);
$pdf->Cell(60,8,'INVOICE',1,1,'C');


$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Bill To :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->customer_name,0,1,'');


$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Invoice no :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->invoice_id,0,1,'');



$pdf->SetFont('Arial','BI',8);
$pdf->Cell(20,4,'Date :',0,0,'');


$pdf->SetFont('Courier','BI',8);
$pdf->Cell(40,4,$row->order_date,0,1,'');


$pdf->SetX(7);
$pdf->SetFont('Courier','B',8);

$pdf->Cell(34,5,'PRODUCT',1,0,'C');   //70
$pdf->Cell(11,5,'QTY',1,0,'C');
$pdf->Cell(8,5,'PRC',1,0,'C');
$pdf->Cell(12,5,'TOTAL',1,1,'C');


$select=$pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
$select->execute();

while($item=$select->fetch(PDO::FETCH_OBJ)){
    $pdf->SetX(7);
  $pdf->SetFont('Helvetica','B',8);
$pdf->Cell(34,5,$item->product_name,1,0,'L');   
$pdf->Cell(11,5,$item->qty,1,0,'C');
$pdf->Cell(8, 5,$item->price,1,0,'C');
$pdf->Cell(12,5,$item->price*$item->qty,1,1,'C');  
    
}


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');

$pdf->Cell(25,5,'SUBTOTAL',1,0,'C');
$pdf->Cell(20,5,$row->subtotal,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');

$pdf->Cell(25,5,'DISCOUNT',1,0,'C');
$pdf->Cell(20,5,$row->discount,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',10);
$pdf->Cell(20,5,'',0,0,'L');   //190

$pdf->Cell(25,5,'GRAND TOTAL',1,0,'C');
$pdf->Cell(20,5,$row->total,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190

$pdf->Cell(25,5,'PAID',1,0,'C');
$pdf->Cell(20,5,$row->paid,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190

$pdf->Cell(25,5,'DUE',1,0,'C');
$pdf->Cell(20,5,$row->due,1,1,'C');


$pdf->SetX(7);
$pdf->SetFont('courier','B',8);
$pdf->Cell(20,5,'',0,0,'L');   //190

$pdf->Cell(25,5,'PAYMENT TYPE',1,0,'C');
$pdf->Cell(20,5,$row->payment_type,1,1,'C');



$pdf->Output();
?>