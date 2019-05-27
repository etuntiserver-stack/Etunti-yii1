<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Ohjevideot'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">



   <?php
	Yii::app()->db1->setActive(true);
	if(!isset($_GET['id'])){
		$model = Ohjevideot::model()->findAll(array('order'=>'id DESC'));
		echo '<div class="row">';
	   	foreach($model as $data) 
	   	{
	 	echo '
		 <div class="col-sm-2 col-md-4" style="margin-bottom:20px">
			<video class="img-thumbnail" controls="controls" style="width:100%">
			  <source src="../../ohjevideot/'.$data->tiedoston_nimi.'" type="video/mp4">
			</video>'
			. CHtml::link('<b>'.$data->otsiko.'</b><br>'.$data->kuvaus, array('ohjevideot', 'id' => $data->id)).'
		 </div>
		';
	   	}
		echo '</div>';
	} else {
		$model = Ohjevideot::model()->findByPk($_GET['id']);
		if(isset($model->id)){
		echo CHtml::link(Yii::t('main', 'Näytä kaikki'), array('ohjevideot'));
	 	echo '<div class="row">
		 <div class="col-sm-8 col-sm-offset-2" style="margin-bottom:20px">
			<video class="img-thumbnail" controls="controls" style="width:100%">
			  <source src="../../ohjevideot/'.$model->tiedoston_nimi.'" type="video/mp4">
			</video>'
			. CHtml::link('<b>'.$model->otsiko.'</b><br>'.$model->kuvaus, array('ohjevideot', 'id' => $model->id)).'
		 </div></div>';
		}
	}

   ?>
 </div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
