<?php
$site = Yii::app()->createController('Site');
$checkLaaja = "tyontekijat_4_".Yii::app()->user->adminStatus;
$laaja = $site[0]->checkOikeusFields($checkLaaja);
?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main','Luo työntekijä'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <?php echo $this->renderPartial('_form', array('model'=>$model, 'laaja' =>$laaja)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

