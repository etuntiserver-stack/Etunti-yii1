<?php

?>

        <!-- begin: .tray-center -->
        <div class="tray-center">


            <div class="admin-form">
              <div class="panel heading-border">
                <div class="panel-body bg-light">
                 <div class="row">

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'cssFile' => Yii::app()->request->baseUrl.'/css/profile.css',
	'attributes'=>array(
		//'id',
		'asiakasnumero',
		//'time',
		'yrityksen_nimi',
		'y_tunnus',
		'yhteyshenkilo',
		'osoite',
		'kaupunki',
		'postinumero',
		'puhelin',
		'sahkoposti',
		'ryhma',
		//'aktiivinen',
	),
)); ?>

                 </div>
                </div>
              </div>
            </div>

        <!-- loppu: .tray-center -->
        </div>

