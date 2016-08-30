<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Lasku netvisor ID#: ').$id; ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/lasku/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>




            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
<?php

	
 if($result->ResponseStatus->Status == 'OK')
 {
  echo '<h2>'.Yii::t('main', 'InvoiceStatus').': '.$result->SalesInvoice->InvoiceStatus.'</h2>';

  echo '
  <form action="#" method="POST">
  <div class="row">
   <div class="col-sm-3">

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'InvoicingCustomerName').'</label>
	 <input type="text" class="form-control" name="Invoicing_Customer_Name" value="'.$result->SalesInvoice->InvoicingCustomerName.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'InvoicingCustomerAddressline').'</label>
	 <input type="text" class="form-control" name="Invoicing_Customer_Address_Line" value="'.$result->SalesInvoice->InvoicingCustomerAddressline.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'InvoicingCustomerPostnumber').'</label>
	 <input type="text" class="form-control" name="Invoicing_Customer_Postnumber" value="'.$result->SalesInvoice->InvoicingCustomerPostnumber.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'InvoicingCustomerTown').'</label>
	 <input type="text" class="form-control" name="Invoicing_Customer_Town" value="'.$result->SalesInvoice->InvoicingCustomerTown.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'DeliveryAddressName').'</label>
	 <input type="text" class="form-control" name="Delivery_Address_Name" value="'.$result->SalesInvoice->DeliveryAddressName.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'DeliveryAddressLine').'</label>
	 <input type="text" class="form-control" name="Delivery_Address_Line" value="'.$result->SalesInvoice->DeliveryAddressLine.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'DeliveryAddressPostnumber').'</label>
	 <input type="text" class="form-control" name="Delivery_Address_Postnumber" value="'.$result->SalesInvoice->DeliveryAddressPostnumber.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'DeliveryAddressTown').'</label>
	 <input type="text" class="form-control" name="Delivery_Address_Town" value="'.$result->SalesInvoice->DeliveryAddressTown.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'InvoicingCustomerTown').'</label>
	 <input type="text" class="form-control" name="Invoicing_Customer_Town" value="'.$result->SalesInvoice->InvoicingCustomerTown.'">
	</div>

   </div>
   <div class="col-sm-3">

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceNumber').'</label>
	 <input type="text" class="form-control" name="Sales_Invoice_Number" value="'.$result->SalesInvoice->SalesInvoiceNumber.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceDate').'</label>
	 <input type="text" class="form-control datepickerFI" name="Sales_Invoice_Date" value="'.date("d.m.Y", strtotime($result->SalesInvoice->SalesInvoiceDate)).'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceDeliveryDate').'</label>
	 <input type="text" class="form-control datepickerFI" name="Sales_Invoice_Delivery_Date" value="'.date("d.m.Y", strtotime($result->SalesInvoice->SalesInvoiceDeliveryDate)).'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceDueDate').'</label>
	 <input type="text" class="form-control datepickerFI" name="Sales_Invoice_Due_Date" value="'.date("d.m.Y", strtotime($result->SalesInvoice->SalesInvoiceDueDate)).'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceReferencenumber').'</label>
	 <input type="number" class="form-control" name="Sales_Invoice_Reference_Number" value="'.$result->SalesInvoice->SalesInvoiceReferencenumber.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'SalesInvoiceAmount').'</label>
	 <input type="number" class="form-control" name="Sales_Invoice_Amount" value="'.str_replace(",", ".", $result->SalesInvoice->SalesInvoiceAmount).'">
	</div>
   </div>
   <div class="col-sm-3">
	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'PaymentTermNetDays').'</label>
	 <input type="number" class="form-control" name="Payment_Term_Net_Days" value="'.$result->SalesInvoice->PaymentTermNetDays.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'PaymentTermCashDiscountDays').'</label>
	 <input type="number" class="form-control" name="Payment_Term_Cash_Discount_Days" value="'.$result->SalesInvoice->PaymentTermCashDiscountDays.'">
	</div>

	<div class="section fill mb5">
	 <label>'.Yii::t('main', 'PaymentTermCashDiscount').'</label>
	 <input type="number" class="form-control" name="Payment_Term_Cash_Discount" value="'.$result->SalesInvoice->PaymentTermCashDiscount.'">
	</div>

   </div>
  </div>

  <hr>
  <h3>'.Yii::t('main', 'Rivit').'</h3>
  <div class="row">
   <table class="table">
   ';
   foreach($result->SalesInvoice->InvoiceLines->InvoiceLine->SalesInvoiceProductLine as $rivi)
   {
	//     <td>'.$rivi->productidentifier.'</td>

   echo '
    <tr>
     <td><input type="text" class="form-control" name="InvoiceLine[ProductName][]" value="'.$rivi->ProductName.'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[ProductUnitPrice][]" value="'.number_format(str_replace(",", ".", $rivi->ProductUnitPrice), 2, '.', '').'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[ProductPurchasePrice][]" value="'.number_format(str_replace(",", ".", $rivi->ProductPurchasePrice), 2, '.', '').'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[ProductVatPercentage][]" value="'.str_replace(",", ".", $rivi->ProductVatPercentage).'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[SalesInvoiceProductLineQuantity][]" value="'.number_format(str_replace(",", ".", $rivi->SalesInvoiceProductLineQuantity), 2, '.', '').'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[SalesInvoiceProductLineDiscountPercentage][]" value="'.str_replace(",", ".", $rivi->SalesInvoiceProductLineDiscountPercentage).'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[SalesInvoiceProductLineVatSum][]" value="'.number_format(str_replace(",", ".", $rivi->SalesInvoiceProductLineVatSum), 2, '.', '').'"></td>
     <td><input type="number" class="form-control" name="InvoiceLine[SalesInvoiceProductLineSum][]" value="'.number_format(str_replace(",", ".", $rivi->SalesInvoiceProductLineSum), 2, '.', '').'"></td>
    </tr>';
   }
 echo '
   </table>
  </div>
  <br>
  <input type="submit" class="btn btn-success" value="'.Yii::t('main', 'Tallenna').'">
  </form>
  ';

/*
	echo '<pre>';
	print_r( $result );
	echo '</pre>';
*/
	
 } else {
	echo '<pre>';
	print_r( $result );
	echo '</pre>';
 }


?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>
