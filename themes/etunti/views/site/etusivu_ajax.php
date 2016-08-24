<?php


if($suoritus == 'cronin_asiat')
{

	// <-- ylittaneet
	$ylittaneet = '';
	$criteria=new CDbCriteria;
	$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		AND ilmoitus_avoimista_kohteesta=1
	";
	$tv = Tyovuoroot::model()->findAll($criteria);
	
	if(count($tv) > 0)
	{
	  foreach($tv as $dat)
	  {
		$k = Kohteet::model()->findbypk($dat->kohde);
		$t = Tyontekijat::model()->findbypk($dat->tid);
		if(isset($t->id) and isset($k->id))
		{

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			kohdenID='".$k->id."' AND tid='".$t->id."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d", strtotime($dat->pvm))."'
			AND (status=1 OR status=3)
		";
		$mob = Mobile::model()->find($criteria);

		$tilanne = '';
		if( isset($mob->id) and $mob->status == 1 )
		$tilanne = '<span class="text-danger">'.Yii::t('main', 'Avoin').'</span>';
		elseif( isset($mob->id) and $mob->status == 3 )
		$tilanne = '<span class="text-success">'.Yii::t('main', 'Lopetettu klo:').' '.date("H:i", strtotime($mob->loppui)).'</span>';

		$ylittaneet .= '<tr><td><span class=""></span> '.$t->tekijan_nimi.'<br>'.$k->osoite.'</td><td>'.$dat->alku.'-'.$dat->loppu.'<br>'.$tilanne.'</td></tr>';

		}
	  }
	
	}
	// ylittaneet -->


	// <-- myohastyneet
	$myohastyneet = '';
	$criteria=new CDbCriteria;
	$criteria->condition = " 
		DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%Y-%m-%d') = CURDATE()
		AND ilmoitus_myohastyneista_kohteesta=1
	";
	$tv = Tyovuoroot::model()->findAll($criteria);
	
	if(count($tv) > 0)
	{
	  foreach($tv as $dat)
	  {
		$k = Kohteet::model()->findbypk($dat->kohde);
		$t = Tyontekijat::model()->findbypk($dat->tid);
		if(isset($t->id) and isset($k->id))
		{

		$criteria=new CDbCriteria;
		$criteria->condition = " 
			kohdenID='".$k->id."' AND tid='".$t->id."'
			AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d", strtotime($dat->pvm))."'
		";
		$mob = Mobile::model()->find($criteria);

		$tilanne = '';
		if( !isset($mob->id) )
		$tilanne = '<span class="text-danger">'.Yii::t('main', 'Myöhässä:').' '.$this->sprint(time()-strtotime($dat->alku)).'</span>';

		$myohastyneet .= '<tr><td><span class=""></span> '.$t->tekijan_nimi.'<br>'.$k->osoite.'</td><td>'.$dat->alku.'-'.$dat->loppu.'<br>'.$tilanne.'</td></tr>';
		}
	  }
	
	}
	// myohastyneet -->

	echo json_encode(array($ylittaneet,$myohastyneet));
}
?>
