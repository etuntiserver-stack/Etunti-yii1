<?php
$site = Yii::app()->createController('Site');
$criteria = new CDBCriteria;
$criteria->limit = "200";
$criteria->condition = "
	DATE(STR_TO_DATE(pto, '%d.%m.%Y')) > '2021-01-01'
	AND korjattu_poista_tama=0
	AND viikkoja!='1'
";
//	AND viikkoja!='1'
//	AND id=3888
// 193730
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

		if(!isset($_GET['go'])){
		echo '<h4>#'.$item->id.', '.$item->pfrom.' - '.$item->pto.',  Joka: '.$item->viikkoja.' vko.</h4><br>';
		echo '<table class="table table-bordered" style="width:50%">
		<tr><th>Nykyinen ketju (Poistetaan kaikki)</th><th>Uusi ketju muutoksen jalkeen</th></tr>
		<tr><td style="vertical-align:top">
		';
		foreach($tv as $tv_item){
			echo $site[0]->etuSukunimi($tv_item->tid).'  <b>'.$tv_item->pvm.'</b><br>';
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
			$new_tv->attributes = $attributes;
			$new_tv->tid = $tid;
			$new_tv->pvm = $wk->format('d.m.Y');
			if(!isset($_GET['go']))
			echo $site[0]->etuSukunimi($new_tv->tid).' <b>'.$wk->format('d.m.Y').' '.$new_tv->osoite.'</b><br>';
			// <-- GO
			if(isset($_GET['go'])){
				$new_tv->save();
			}
		   }
		}
		if(!isset($_GET['go'])){
		echo '</td></tr></table>';
		echo '<hr>';
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
