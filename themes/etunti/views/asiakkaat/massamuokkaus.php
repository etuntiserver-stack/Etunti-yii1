<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakkaiden hallinta'); ?>: <?php echo Yii::t('main', 'massamuokkaus'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
		  <p>
			Suodattimella valitaan asiakasryhmä, jolle muokkaukset tehdään. <br>
			Asiakas- ja laskutustiedoista valitaan muokattavat kentät valintalaatikolla (checkbox) sekä syötetään haluttu tieto. <br>
			Esikatselutilassa näytetään lista asiakkaista, joilla muutokset tehdään. </p><br>

		  <?php echo $this->renderPartial('_massamuokkaus', array('model'=>$model)); ?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>


