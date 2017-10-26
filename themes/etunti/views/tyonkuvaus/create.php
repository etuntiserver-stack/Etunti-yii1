<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Uusi työnkuvaus'); ?> </h2>

	   <p><?php echo CHtml::link('<span class="myBgColors btn btn-success">'.Yii::t('main', 'Luo asiakas').'</span>',Yii::app()->request->baseUrl.'/index.php/asiakkaat/create',array('data-toggle'=>'tooltip', 'data-placement'=>'top', 'title' => Yii::t('main', 'Lisää asiakas') )); ?></p>

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

