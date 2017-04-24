<?php
/*
  if (!file_exists(Yii::app()->basePath."/../tiedostot/templates/".Yii::app()->user->domain."/palvelusopimus_kuluttajat.docx")) {
  	echo '<h1 class="alert alert-danger">'.Yii::t('main', 'Template tiedosto puuttuu').'</h1>';
  }
*/
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'LUO sopimus'); ?> </h2>


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


