<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Ohjevideot'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">



 <div class="row">
   <?php
	Yii::app()->db1->setActive(true);
	$model = Ohjevideot::model()->findAll(array('order'=>'sort ASC'));
   	foreach($model as $data) 
   	{

	 	echo '
		 <div class="col-sm-4" style="margin-bottom:20px">
			<video class="img-thumbnail" controls="controls" style="width:100%">
			  <source src="../../ohjevideot/'.$data->tiedoston_nimi.'" type="video/mp4">
			</video>
			<b>'.$data->otsiko.'</b><br>
			'.$data->kuvaus.'
		 </div>
		';

   	}
   ?>
 </div>



                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
