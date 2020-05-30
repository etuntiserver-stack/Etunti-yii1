<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="fa fa-file-video-o"></i> <?php echo Yii::t('main', 'Katso video'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

			<?php $this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					'id',
					'time',
					'otsiko',
					'embed',
					'kuvaus',
					//'tiedoston_nimi',
					//'sort',
				),
			)); ?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

