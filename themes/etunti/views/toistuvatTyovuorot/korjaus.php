<?php
/*
Hossain Iqbal
Kovalenko Valentyna
Lepik Marius
Ongachi
Peipsi
Rahkema Aira
Tornea Kimberly
Zerouali Ali
*/

		$toistuva = ToistuvatTyovuorot::model()->findbypk(3559);
		if( is_array(json_decode($toistuva->poistettu_pvm, true)) ){

			$tids = [];
			if( !empty($toistuva->tyopaari) ){
				foreach(json_decode($toistuva->tyopaari, true) as $tid){
					$tids[$tid] = $tid;
				}
				$tids[$toistuva->tid] = $toistuva->tid;
			} else {
				$tids[$toistuva->tid] = $toistuva->tid;
			}

			if( empty($toistuva->new_poistettu_pvm) ){
				$new_poistettu_pvm = [];
				foreach($tids as $tid)
					foreach(json_decode($toistuva->poistettu_pvm, true) as $k => $v)
						$new_poistettu_pvm[] = [$tid => $v];

				$clearing = [];
				foreach ($new_poistettu_pvm as $key => $value){
				  if(!in_array($value, $clearing))
				    $clearing[] = $value;
				}

				//echo json_encode($clearing);
				ToistuvatTyovuorot::model()->updatebypk($toistuva->id, array('new_poistettu_pvm'=>json_encode($clearing)));
			}
			return true;
		}
exit;
/*
$site = Yii::app()->createController('Site');
$criteria = new CDBCriteria;
$criteria->limit = "50";
$criteria->condition = "
	DATE(STR_TO_DATE(pto, '%d.%m.%Y')) > '2021-01-01'
	AND korjattu_poista_tama=0
	AND viikkoja!='1'
";
//	AND viikkoja!='1'
//	AND id=3888
// $2y$10$SxO2lt2rcWJdUdtTYHHqp.7hmjiwpYXtjmBaMgGX7KYTuZZzVyr16
$data = ToistuvatTyovuorot::model()->findAll($criteria);
echo count($data).'<br><br>';
foreach($data as $item){
	$criteria = new CDBCriteria;
	$criteria->order = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))";
	$criteria->condition = "
		DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) > '2020-11-01'			
		AND toistuva_id='".$item->id."'
		AND toistuva_id!=0
	";
	$tv = Tyovuoroot::model()->findAll($criteria);

	// <-- GO
	if(isset($_GET['go'])){
	Tyovuoroot::model()->deleteAll($criteria);
	ToistuvatTyovuorot::model()->updateByPk($item->id, array('korjattu_poista_tama' => '1'));
	}

	if( isset($tv[0]->id) ){
		echo '<h3>'.$item->pfrom.' - '.$item->pto.',  Joka: '.$item->viikkoja.' vko.,  Kohde/osoite ID: '.$item->kohde.'</h3><br>';
		if(!isset($_GET['go'])){
		echo '<table class="table table-bordered" style="width:50%" border="1">
		<tr><th>Nykyinen ketju</th><th>Uusi ketju muutoksen jalkeen</th></tr>
		<tr><td style="vertical-align:top">
		';
		foreach($tv as $tv_item){
			echo 'Siivoja ID:'.$tv_item->tid.',  Vanha pvm:<b>'.$tv_item->pvm.'</b> <span style="color:red">(poistetaan)</span><br>';
		}
		}

		$tids = [];
		foreach($tv as $tv_item){
			if(isset($tids[$tv_item->tid])){ continue; }
			$tids[$tv_item->tid] = $tv_item->attributes;
		}

		$startDate	= date("Y-m-d", strtotime($tv[0]->pvm));
		$end_date	= date("Y-m-d", strtotime($item->pto));

		$weeks = new DatePeriod(
		    new DateTime($startDate), 
		    new DateInterval('P'.$item->viikkoja.'W'), 
		    new DateTime($end_date)
		);
		if(!isset($_GET['go']))
		echo '</td><td style="vertical-align:top">';
		foreach($tids as $tid => $attributes){
		   foreach ($weeks as $wk) {
			$new_tv = new Tyovuoroot();
			unset($attributes['id'],$attributes['time']);
			$new_tv->attributes = $attributes;
			$new_tv->tid = $tid;
			$new_tv->pvm = $wk->format('d.m.Y');
			if(!isset($_GET['go']))
			echo 'Siivoja ID:'.$new_tv->tid.' <b>'.$wk->format('d.m.Y').' Uusi pvm.'.$new_tv->osoite.'</b> <span style="color:green">(luodaan)</span><br>';
			// <-- GO
			if(isset($_GET['go'])){
				$new_tv->save();
			}
		   }
		}
		if(!isset($_GET['go'])){
		echo '</td></tr></table>';
		}
	}
}

/*
		echo '<pre>';
		print_r($tv->attributes);
		echo '</pre>';
		break;
*/
?>
