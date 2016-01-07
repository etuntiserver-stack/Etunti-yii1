<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'MERKKIPÄIVÄT'); ?> </h2>

        <!-- loppu: .tray-center -->
        </div>


  <br>

  <table class="table table-striped table-bordered">
  <thead class="myBgColors">
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Syntymäpäivä'); ?></th>
  <th><?php echo Yii::t('main', 'Henkilötunnus'); ?></th>
  </tr>
  </thead>

  <?php 
  foreach($model as $data)
  {
	$this->renderPartial('_merkkipaivat',array('data'=>$data));
  }
  ?>

  </table>
