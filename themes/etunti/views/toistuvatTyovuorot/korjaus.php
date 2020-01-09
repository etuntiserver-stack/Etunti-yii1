<?php
	$tv_controller = Yii::app()->createController('Tyovuoroot');
	$pvm = "09.01.2020";
	$tid = 29;
	$tids = ["29"];
	$pvm_from = date("Y-m-d", strtotime($pvm));
	$pvm_to = date("Y-m-d", strtotime($pvm));
	$tv_arr = $tv_controller[0]->tv_arr($pvm_from, $pvm_to, $tids, $asiakas='', $kohde='', $kohteet_siivous=[], false);
	if( isset($tv_arr[$tid][$pvm]) ){
		ksort($tv_arr[$tid][$pvm]);
		foreach($tv_arr[$tid][$pvm] as $k => $v){
			foreach($v as $v2){
				echo $v2['this_id'].' '.$v2['osoite'].' '.$tv_controller[0]->tilanteet()[$v2['status']].'<br>';
			}
		}
	}


/*
	// <-- toistuvat
	$start_haku = '2019-11-15';
	$stop_haku = '2020-02-01';
	echo '<h1>Ketjut joista löytyi ongelmia: '.date("d.m.Y", strtotime($start_haku)).' - '.date("d.m.Y", strtotime($stop_haku)).' välissä</h1>';
	$criteria = new CDbCriteria();
	//$criteria->order = "DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))";
	$criteria->select = "id, tid, toistuva_id, MAX(DATE(STR_TO_DATE(pvm, '%d.%m.%Y'))) as pvm, osoite, status, kohde, tyoajanlaatu";
	$criteria->group = "toistuva_id, tid";
	//$criteria->limit = "10";
	$criteria->condition = " 
		DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '$start_haku' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) <=  '2019-12-15'
		AND toistuva_id!=0
	";
	$t = Tyovuoroot::model()->findAll($criteria);
	echo 'Yhteensä: '.count($t).'<br>';
	$i = 0;
	echo '<table border="1" cellpadding="10">';
	foreach($t as $attr){
		if (strtotime($attr->toistuvat->pto) < strtotime($stop_haku))
		continue;

		$body = '';
		$body .= '<tr><td valign="top">';
		$body .= '<h2>Ketju: '.$attr->toistuvat->id.'</h2>';
		if( isset($attr->tt->id) )
		$body .= '<b>Työntekijä</b>: '.$attr->tt->tekijan_nimi.' '.$attr->tt->sukunimi.'<br>';
		if(!empty($attr->tyoajanlaatu)){
				$expl = explode("/",$attr->tyoajanlaatu);
				if(isset($expl[0]) and !empty($expl[0])){
					$body .= $expl[0].'<br>';
				}
		}
		if(!empty($attr->osoite))
			$body .= '<b>Osoite</b>: '.$attr->osoite.'<br>';
		elseif(isset($attr->kohteet->osoite))
			$body .= '<b>Osoite</b>: '.$attr->kohteet->osoite.'<br>';
		$body .= '<b>Aloitus / Lopetus PVM</b>: '. $attr->toistuvat->pfrom.' / '.$attr->toistuvat->pto.'<br>';
		$body .= '<b>Klo</b>: '. $attr->toistuvat->alku.' / '.$attr->toistuvat->loppu.'<br>';
		$body .= '<b>Viikkoja</b>: '.$attr->toistuvat->viikkoja.'<br>';
		$body .= '<b>Vko päivät</b>: '.$attr->toistuvat->viikko_paivat.'<br><br>';
		$body .= '</td><td valign="top">';
 		$body .= '<b>Tarkistuksen päivä aloitus: </b><br> '.date("d.m.Y", strtotime($attr->pvm)).'<br><br>';
		$body .= '</td><td valign="top">';
		$body .= '<b>Ketjun poistetut päivät:</b> <br>';
		$poistettu = [];
		foreach(json_decode($attr->toistuvat->poistettu_pvm, true) as $ppvm) {
			if( strtotime($ppvm) >= strtotime($start_haku) and strtotime($ppvm) <= strtotime($stop_haku) )
		    		$poistettu[strtotime($ppvm)] = $ppvm;
		}
		ksort($poistettu);
		$new_poistettu = [];
		foreach($poistettu as $pvm_p){
			$body .= $pvm_p.'<br>';
			$new_poistettu[$pvm_p] = $pvm_p;
		}

		$body .= '</td><td valign="top">';
		$body .= '<b>On olemassa: </b><br>';

		$criteria = new CDbCriteria();
		$criteria->select = "pvm";
		//$criteria->limit = "10";
		$criteria->condition = " 
			DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) >= '".$attr->pvm."' AND DATE(STR_TO_DATE(pvm, '%d.%m.%Y')) <=  '$stop_haku'
			AND toistuva_id='".$attr->toistuva_id."'
			AND tid='".$attr->tid."'
		";
		$t_next = Tyovuoroot::model()->findAll($criteria);
		$olemassa = [];
		foreach($t_next as $item){
			$body .= $item->pvm.'<br>';
			$olemassa[$item->pvm] = $item->pvm;
		}
		$body .= '</td><td valign="top">';
		$body .= '<b>Pitää olla:</b><br>';
		$startday	= date("Y-m-d", strtotime($attr->pvm));
		$stopday	= '2020-04-01';

		$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
		$date->modify('this week monday');
		$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();

		$pitaa_olla = [];
		while ($date->getTimestamp() < $date_end){
			foreach(json_decode($attr->toistuvat->viikko_paivat, true) as $viikko_paiva) {
				$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
				$paiva->modify("+" . ($viikko_paiva - 1) . "day");
				$pvm = $paiva->format('d.m.Y');
				if (strtotime($pvm) < strtotime($startday))
					continue;
				//if (strtotime($pvm) == strtotime($startday))
					//continue;
				if( isset($new_poistettu[$pvm]) )
					continue;

				if( strtotime($pvm) > strtotime($stop_haku) ){
					break 1;
					break;
				}

				$body .= $pvm.'<br>';
				$pitaa_olla[$pvm] = $pvm;
			}
			$date->modify("+{$attr->toistuvat->viikkoja}week");
		}
		$body .= '</td></tr>';

		$diff = array_diff($olemassa, $pitaa_olla);
		$diff2 = array_diff($pitaa_olla, $olemassa);
		if( count($diff) > 0 or count($diff2) > 0 ){
			echo $body;
			$i++;
		}
		//	echo $body;
	}
	echo '</table>';
	echo $i;
*/

/*
		$startday	= date("Y-m-d", strtotime($attr->pfrom));
		$stopday	= date("Y-m-d", strtotime($attr->pto));

		$date = new \DateTime($startday, new DateTimeZone('Europe/Helsinki'));
		$date->modify('this week monday');
		$date_end = (new \DateTime($stopday, new DateTimeZone('Europe/Helsinki')))->getTimestamp();

		while ($date->getTimestamp() < $date_end){
			foreach(json_decode($attr->viikko_paivat, true) as $viikko_paiva) {
				$paiva = new \DateTime($date->format('Y-m-d'), new DateTimeZone('Europe/Helsinki'));
				$paiva->modify("+" . ($viikko_paiva - 1) . "day");
				$pvm = $paiva->format('d.m.Y');
				if (strtotime($pvm) < strtotime($startday))
					continue;
				$body .= $pvm.'<br>';
			}
			$date->modify("+{$attr->viikkoja}week");
		}
*/

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
$body .= count($data).'<br><br>';
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
		$body .= '<h3>'.$item->pfrom.' - '.$item->pto.',  Joka: '.$item->viikkoja.' vko.,  Kohde/osoite ID: '.$item->kohde.'</h3><br>';
		if(!isset($_GET['go'])){
		$body .= '<table class="table table-bordered" style="width:50%" border="1">
		<tr><th>Nykyinen ketju</th><th>Uusi ketju muutoksen jalkeen</th></tr>
		<tr><td style="vertical-align:top">
		';
		foreach($tv as $tv_item){
			$body .= 'Siivoja ID:'.$tv_item->tid.',  Vanha pvm:<b>'.$tv_item->pvm.'</b> <span style="color:red">(poistetaan)</span><br>';
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
		$body .= '</td><td style="vertical-align:top">';
		foreach($tids as $tid => $attributes){
		   foreach ($weeks as $wk) {
			$new_tv = new Tyovuoroot();
			unset($attributes['id'],$attributes['time']);
			$new_tv->attributes = $attributes;
			$new_tv->tid = $tid;
			$new_tv->pvm = $wk->format('d.m.Y');
			if(!isset($_GET['go']))
			$body .= 'Siivoja ID:'.$new_tv->tid.' <b>'.$wk->format('d.m.Y').' Uusi pvm.'.$new_tv->osoite.'</b> <span style="color:green">(luodaan)</span><br>';
			// <-- GO
			if(isset($_GET['go'])){
				$new_tv->save();
			}
		   }
		}
		if(!isset($_GET['go'])){
		$body .= '</td></tr></table>';
		}
	}
}

/*
		$body .= '<pre>';
		print_r($tv->attributes);
		$body .= '</pre>';
		break;
*/
?>
