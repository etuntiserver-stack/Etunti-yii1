<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


              <h2 class="myBgColors p10"> <i class="fa fa-indent"></i> <?php echo Yii::t('main', 'Merkkipäivät'); ?> </h2>

        <!-- loppu: .tray-center -->
        </div>


  <br>

<div class="admin-form">
  <div class="panel heading-border">
   <div class="panel-body">

<div class="row">
 <div class="table-responsive">
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
 </div>
</div>

   </div>
  </div>
</div>
