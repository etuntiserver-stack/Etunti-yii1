<?php
exit;
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-envelope"></i> <?php echo Yii::t('main', 'Lähetä viesti mobiilisovellukseen'); ?> </h2>

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
