<?php

$this->menu=array(
	array('label'=>'Poista asiakas', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),

);
?>

        <!-- begin: .tray-center -->
        <div class="tray-center">

	   <h2> <i class="glyphicon glyphicon-home"></i> <?php echo Yii::t('main', 'ASIAKAS')." ID# ".$model->id; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		'id',
		'asiakasnumero',
		'time',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'kaupunki',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		'aktiivinen',
	),
)); ?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

