<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'Luo hinnasto'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model, 'tp'=>$tp, 'yksikkot'=>$yksikkot, "mode" => $mode)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>
