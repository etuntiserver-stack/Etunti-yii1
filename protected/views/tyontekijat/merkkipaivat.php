<?php

?>

<legend>
<h1> <?php echo Yii::t('main', 'MERKKIPÄIVÄT'); ?> <i class="glyphicon glyphicon-user"></i></h1>
</legend>

  <br>

  <table class="table table-striped table-bordered">
  <thead>
  <tr>
  <th><?php echo Yii::t('main', 'Työntekijä'); ?></th>
  <th><?php echo Yii::t('main', 'Syntäri'); ?></th>
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
