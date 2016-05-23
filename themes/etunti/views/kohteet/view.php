<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <div class="pull-right">
	   <?php     
		echo CHtml::link(Yii::t('main', 'Palaa takaisin muokkaamaan'), 'update?id='.$model->id, array(
		'class'=>'btn btn-default'
		));
	   ?>
	   </div>

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo $model->osoite; ?> </h2>


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
		'tag_id',
		'gps_sijainti',
		'lyhenne',
		'osoite',
		'katuosoite',
		'kaupunki',
		'toimipaikka',
		'pnumero',
		'email',
		'aikataulu',
		'hinnoittelu',
		'muut',
		'toimenpiteet',
		'tietoja',
		'tyoryhma',
		'ryhma',
		'aktiivinen',
		'avain',
		'kenella_on_avain',
		'puh_nro',
		'siivous',
		'etu_suku_nimet',
		'maksuehto_paiva',
		'viivastyskorko',
		'lasku_tiedot',
	),
)); ?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>
