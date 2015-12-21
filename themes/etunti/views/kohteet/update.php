<?php

?>

  <div class="panel heading-border">
   <div class="panel-body bg-light">
      <div class="section-divider mb40" id="spy1">
      <span><?php echo Yii::t('main', 'KOHDE')." ID# ".$model->id; ?> <i class="glyphicon glyphicon-home"></i></span>
      </div>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

   </div>
  </div>
