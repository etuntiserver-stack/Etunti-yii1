<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Luo järjestelmävalvoja'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

		  <div class="col-sm-3">
		  <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
		  </div>

                 </div>
                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>

