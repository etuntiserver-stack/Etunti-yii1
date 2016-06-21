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
   foreach(glob(Yii::app()->baseUrl.'ohjevideot/*') as $file) 
   {

	$explNimi = explode("/",$file);
	if(!empty(end($explNimi)))
	{
 	echo '<p>
	<div class="row">
		<video class="img-thumbnail" controls="controls">
		  <source src="../../'.$file.'" type="video/mp4">
		</video>
	</div></p>
	';
	}
   }
   ?>
 </div>
</div>


                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
