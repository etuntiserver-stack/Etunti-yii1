<?php
$this->menu=array(
	array('label'=>'Poista kohde', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Oletko varmaa?')),
);
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo $model->osoite; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

