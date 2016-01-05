<?php
$this->menu=array(
	array('label'=>'Poista kohde', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'KOHDE')." ID# ".$model->id; ?> </h2>


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
