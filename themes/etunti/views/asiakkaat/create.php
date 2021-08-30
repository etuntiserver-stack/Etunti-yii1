<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <?php     
		$site = Yii::app()->createController('Site');
		$site[0]->oikeudet($model->id,'noDelete');
	   ?>
	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-user"></i> <?php echo Yii::t('main', 'Asiakkaiden hallinta'); ?>: <?php echo Yii::t('main', 'Luo asiakas'); ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">
                 	<?php
                 	$h_all 	= TuotteetPalvelut::model()->findAll("aktiivinen=1 AND yksikko='h'");
             		if(count($h_all) == 0)
             		{
             			echo 'Luo ainakin yksi "h" tuote '.CHtml::link('tästä', ['/tuotteetPalvelut/create']).'. <br><br>
             			Suosittelemme myös luomaan <b>"kk"</b> ja <b>"kpl"</b> tuotteet. Tuote on pakollinen kenttä kohteen kortilla.<br>
             			';
             		} else {
             			echo $this->renderPartial('_form', array('model'=>$model));
             		}
                 	?>
                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>


