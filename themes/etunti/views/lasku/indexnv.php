<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'Lasku netvisor'); ?> 
		<?php echo CHtml::link('',Yii::app()->request->baseUrl.'/index.php/lasku/create',array('class'=>'btn btn-default fa fa-plus')); ?>
	</h2>

        </div>



<?php

	
 if($result->ResponseStatus->Status == 'OK')
 {

   echo '
   <table class="table table-hovered table-striped">
    <tr>
     <th>'.Yii::t('main', 'NetvisorKey').'</th>
     <th>'.Yii::t('main', 'InvoiceNumber').'</th>
     <th>'.Yii::t('main', 'Invoicedate').'</th>
     <th>'.Yii::t('main', 'InvoiceStatus').'</th>
     <th>'.Yii::t('main', 'CustomerName').'</th>
     <th>'.Yii::t('main', 'ReferenceNumber').'</th>
     <th>'.Yii::t('main', 'InvoiceSum').'</th>
     <th>'.Yii::t('main', 'OpenSum').'</th>
     <th>'.Yii::t('main', 'Muoka').'</th>
    </tr>
   ';
	foreach($result->SalesInvoiceList->SalesInvoice as $lasku)
	{

		echo '
		<tr>
			<td>'.$lasku->NetvisorKey.'</td>
			<td>'.$lasku->InvoiceNumber.'</td>
			<td>'.$lasku->Invoicedate.'</td>
			<td>'.$lasku->InvoiceStatus.'</td>
			<td>'.$lasku->CustomerName.'</td>
			<td>'.$lasku->ReferenceNumber.'</td>
			<td>'.$lasku->InvoiceSum.'</td>
			<td>'.$lasku->OpenSum.'</td>
			<td>'.CHtml::link('', array('updatenv', 'id'=>$lasku->NetvisorKey), array('class'=>'fa fa-pencil-square-o')).'</td>
		</tr>';

	}

	echo '</table>';
	
 } else {
	echo '<pre>';
	print_r( $response );
	echo '</pre>';
 }


?>
