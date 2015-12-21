<?php

?>

  <div class="panel heading-border">
   <div class="panel-body bg-light">
      <div class="section-divider mb40" id="spy1">
      <span> <?php echo Yii::t('main', 'ASIAKAS')." ID# ".$model->id; ?> <i class="glyphicon glyphicon-user"></i> </span>
      </div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

   </div>
  </div>
