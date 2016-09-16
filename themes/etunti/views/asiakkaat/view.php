<?php


		$startDate	= '08.09.2016';
		$end_date	= '26.12.2016';
		$viikkoja	= 4;

		$date		= $startDate;
		$p 		= array(3=>3);


		$var		= 1;

		if($viikkoja == 1)
			$var	= 0;

		$w		= $p;
		$v 		= $viikkoja;
		$weeksArr = array();
 		while (strtotime($date) <= strtotime($end_date)) {

			$viikonNumero = (date('W',strtotime($date)));
		  	$weeksArr[$viikonNumero] = $viikonNumero;
	                $date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
		}

		$i = 1;
		$sopivaViikot = array();
		foreach($weeksArr as $k=>$result)
		{
		    if($i % $viikkoja === $var) {
		        $sopivaViikot[$result] = $result;
		    }
		    $i++;
		}

		$date		= $startDate;
		$end_date	= $end_date;
		$return = array();
 		while (strtotime($date) <= strtotime($end_date)) {

			$viikonNumero = (date('W',strtotime($date)));

	                if( 
				in_array(date('w',strtotime($date)),$w) 
				and in_array($viikonNumero,$sopivaViikot) 
			)
			{
				$return[] = $date;
			}

	                $date = date ("d.m.Y", strtotime("+1 day", strtotime($date)));
		}


echo '<pre>';
print_r($return); 
echo '</pre>';
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

