<?php


?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"><?php echo Yii::t('main', 'Kiitos'); ?> </h2>

            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">

                 <h2>
		  <?php echo Yii::t('main', 'Vinkki lähetetty.'); ?>
                 </h2>

<?php
   if(isset(Yii::app()->user->asiakas))
		echo CHtml::link(Yii::t('main', 'Palaa takaisin omalle sivullesi'), Yii::app()->request->baseUrl.'/index.php/asiakkaat/asiakas_tila?id='.$asiakas_id, array('class'=>'btn btn-primary'));
   elseif(isset(Yii::app()->user->adminID))
		echo CHtml::link(Yii::t('main', 'Palaa takaisin omalle sivullesi'), Yii::app()->request->baseUrl.'/index.php/asiakkaat/update?id='.$asiakas_id, array('class'=>'btn btn-primary'));
		?>
                </div>
              </div>
            </div>


        <!-- loppu: .tray-center -->
        </div>
