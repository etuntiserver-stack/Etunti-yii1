<?php
/*
if(isset($model->id) and $model->tilanne == '1'){
$this->menu=array(
	array('label'=>'Laskun mitätöinti', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
}
*/
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
		<?php
			echo CHtml::link("poista", '#', array(
				'submit'=>array('delete', "id"=>$model->id), 
				'confirm' => 'Haluatko varmaasti poistaa laskun?',
				'class'=>'btn btn-primary myBgColors'
			));
		?>
	   </div>

	   <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo $model->laskun_nimetys; ?> <?php echo $model->laskunumero; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model, 'laskunRivit'=>$laskunRivit)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>



