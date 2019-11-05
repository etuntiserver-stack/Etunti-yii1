<?php
$criteria = new CDBCriteria;
$criteria->condition = " 				
	DATE(STR_TO_DATE(pto, '%d.%m.%Y')) > '2020-01-01'
	AND korjattu_poista_tama=0
";
//	AND viikkoja!='1'
//	AND id=3888
$data = ToistuvatTyovuorot::model()->findAll($criteria);
echo count($data).'<br><br>';
foreach($data as $item){
	$criteria = new CDBCriteria;
	$criteria->order = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))";
	$criteria->condition = "
		DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) > '2019-11-01'			
		AND toistuva_id='".$item->id."'
	";
	$tv = Tyovuoroot::model()->find($criteria);
	Tyovuoroot::model()->deleteAll($criteria);
	ToistuvatTyovuorot::model()->updateByPk($item->id, array('korjattu_poista_tama' => 1));
	//echo $item->id.' - '.$item->pfrom.' '.$item->pto.' '.count($tv).'<br>';
	
	if( isset($tv->id) ){
		$startDate	= date("Y-m-d", strtotime($tv->pvm));
		$end_date	= date("Y-m-d", strtotime($item->pto));

		$weeks = new DatePeriod(
		    new DateTime($startDate), 
		    new DateInterval('P'.$item->viikkoja.'W'), 
		    new DateTime($end_date)
		);
		foreach ($weeks as $wk) {
			//echo $wk->format('d.m.Y').'<br>';
			$new_tv = new Tyovuoroot();
			$new_tv->attributes = $tv->attributes;
			$new_tv->pvm = $wk->format('d.m.Y');
			if(!$new_tv->save()){
				var_dump($new_tv->getErrors());
				break;
			}
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
