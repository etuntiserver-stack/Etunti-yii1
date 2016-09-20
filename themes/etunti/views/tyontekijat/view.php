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

	   <h2 class="myBgColors p10"> <i class="fa fa-male"></i> <?php echo Yii::t('main', 'TYÖNTEKIJÄ')." ".$model->tekijan_nimi; ?> </h2>


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">


<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		'id',
		'imei',
		'laiten_puh',
		'tekijan_nimi',
		'tekijan_henkilotunnus',
		'tekijan_puh',
		'tekijan_email',
		'tekijan_lanka_puh',
		'tekijan_katuosoite',
		'tekijan_pnumero',
		'tekijan_ptoimipaikka',
		'tyoryhma',
		'tyo_toimialue',
		'tyoehtosopimus',
		'tekijan_kulunvalvonta',
		'tekijan_pankkitili',
		'tekijan_konttori',
		'aktiivinen',
		'tekijan_tietoja',
		'tekijan_muisti',
		'salasana',
		'online_varauksen_valmina',
		'kortit',
		'ayjasenyys',
		'gcm_reg_id',
	),
)); ?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>
