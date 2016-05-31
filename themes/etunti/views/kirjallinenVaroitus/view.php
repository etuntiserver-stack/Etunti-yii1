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

	   <h2 class="myBgColors p10"> <i class="glyphicon glyphicon-home"></i> <?php echo $model->tekijan_nimi; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'time',
		'key',
		'tyonantaja',
		'osoite',
		'postinumero',
		'postitoimipaikka',
		'puhelin',
		'y_tunnus',
		'sahkoposti',
		'tekijan_email',
		'tid',
		'tekijan_nimi',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tekijan_puh',
		'tekijan_henkilotunnus',
		'kirjallisen_varoituksen',
		'Paivays',
		'Paikka',
		'TyonantajanEdustaja',
		'NimikeTehtava',
		'tiedosto',
	),
)); ?>


                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>
