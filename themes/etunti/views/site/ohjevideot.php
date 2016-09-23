<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Ohjevideot'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">


<div class="row">
 <div class="col-sm-6 col-sm-offset-3">
   <?php
	Yii::app()->db1->setActive(true);
	$model = Ohjevideot::model()->findAll();
   	foreach($model as $data) 
   	{

	 	echo '<p>
		<div class="row">
			<h2>'.$data->otsiko.'</h2>
			<video class="img-thumbnail" controls="controls">
			  <source src="../../ohjevideot/'.$data->tiedoston_nimi.'" type="video/mp4">
			</video>
			<h3>'.$data->kuvaus.'</h3>
		</div></p>
		';

   	}
   ?>
 </div>
</div>


                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
