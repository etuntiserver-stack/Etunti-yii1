<?php


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"><?php echo Yii::t('main', 'Vastaus'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <div class="row">
		  <?php echo $this->renderPartial('_vastaus', array('model'=>$model, 'keskustelu_id'=>$keskustelu_id)); ?>
                 </div>

                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
