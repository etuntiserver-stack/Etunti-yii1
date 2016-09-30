<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKAS')." ID# ".$model->id; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		'id',
		'time',
		'yhteystieto_tyyppi',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'postitoimipaikka',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'myyja',
		'status',
	),
)); ?>


                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

