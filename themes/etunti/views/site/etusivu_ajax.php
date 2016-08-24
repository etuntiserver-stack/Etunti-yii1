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
			$ylittaneet .= '<p class="form-inline"><div class="col-sm-3">'.$dat->alku.'-'.$dat->loppu.'</div><div class="col-sm-9">'.$t->tekijan_nimi.'<br>'.$k->osoite.'</div></p>';
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
			$myohastyneet .= '<p class="form-inline"><div class="col-sm-3">'.$dat->alku.'-'.$dat->loppu.'</div><div class="col-sm-9">'.$t->tekijan_nimi.'<br>'.$k->osoite.'</div></p>';
		}
	  }
	
	}
	// myohastyneet -->

	echo json_encode(array($ylittaneet,$myohastyneet));
}
?>
