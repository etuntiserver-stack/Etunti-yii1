<?php
  if( !isset($tyosuhteet_checker[$tt->id]) and isset($ts->loppu) and !empty($ts->loppu) and strtotime($ts->loppu) < strtotime($date) )
  {
	$tyosuhteet_checker[$tt->id] = array('nimi' => $this->etuSukunimi($tt->id), 'tsloppu' => $ts->loppu );
  }

  $criteria = new CDbCriteria();
  $criteria->order = " alku ASC "; 
  $criteria->condition = "  
  tid = '".$tt->id."'
  AND pvm = '$date' 
  AND peruutettu=0
  ";
  if(isset($_POST['P'])){
  $criteria->Addcondition ( " DATE_FORMAT(STR_TO_DATE(pvm, '%d.%m.%Y'), '%w') IN (".implode(",",$_POST['P']).") ");
  }
  $tv = Tyovuoroot::model()->findAll($criteria);
  if( count($tv) > 0 ){
  echo '<tr><td colspan="2"><h2><b>'.$paivat[date('N',$d)].' '.$date.'</b></h2></td></tr>';
  echo '<tr><td valign="top" width="30%"><h5>Aika/Kohde</h5><br>';

  $yht = 0;
  foreach($tv as $t)
  {
	// <-- Asiakas Tiedot
	$asiakasTiedot = '';
	if(isset($t->kohteet->asiakas_id)){
		$as = Asiakkaat::model()->findbypk($t->kohteet->asiakas_id);
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<br><b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yrityksen_nimi;
		} else if(isset($as->yhteyshenkilo) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<br><b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yhteyshenkilo;
		}
		if($asetukset->tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka == 1){
			$asiakasTiedot .= '<br><b>'.Yii::t('main', 'Kohteen postitoimipaikka').':</b> '.$t->kohteet->kaupunki;
		}
	}
	// Asiakas Tiedot -->

	$al = '';
	if($t->alku > 0 and $t->loppu > 0){
	  $al = $t->alku.'-'.$t->loppu;
	  if($site[0]->eiLasketaSubStr($t->tyoajanmerkinta) === false){ $yht += strtotime($t->loppu)-strtotime($t->alku); }
	}

	// <-- osoite
	$osoite = '';
	if(!empty($t->osoite)){
		$osoite = $t->osoite;
	} elseif(isset($t->kohteet->id) and empty($t->osoite)){
		$osoite = $t->kohteet->osoite;
	} elseif(!isset($k->id) and $t->status != 0 and $t->status != 3){
		$osoite = $this->tilanteet()[$t->status];
	}

	$color = '#888';
	$bgcol = 'color:#333';
	if(!empty($t->tyoajanmerkinta)){
		$expl = explode("/",$t->tyoajanmerkinta);
		if(isset($expl[1]) and !empty($expl[1])){
			$color = $expl[1];
			$bgcol = 'color:'.$color;
		}
	}
	if(!empty($t->tyoajanlaatu) and empty($osoite)){
		$expl1 = explode("/",$t->tyoajanlaatu);
		if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
		$osoite = (isset($expl1[0])) ? '<div class="text-center tyoajanlaatu_laatiko" style="background:'.$color.';color:#fff">'.$expl1[0].'</div>' : '';
	}
	//     osoite -->

	echo '<span>'.$al.' '.$osoite.'</span>';
	echo $asiakasTiedot;
	// <-- Avain
	if( isset($t->avaimet) ){
		echo '<br>';
		foreach($t->avaimet as $avain){
			echo '<b>Avain</b> - '.$avain->avainnumero.':'.$this->etuSukunimi($avain->tid).':'.$avain->sijainti.'<br>';
		}
	}
	//     Avain -->
	echo '<p style="padding:0;margin:0">------</p>';

  }
  if($yht > 0)
  echo '<h4>'.Yii::t('main','Yhteensä: ').$this->sprint($yht).'</h4>';
  echo '</td><td valign="top" style="border-left: 1px #ccc solid"><h5>Tietoja</h5><br>';

  foreach($tv as $tvPvm)
  {
	// <-- Osoite
	$osoite = '';
	if(!empty($tvPvm->osoite)){
		$osoite = $tvPvm->osoite;
	} elseif(empty($tvPvm->osoite) and isset($tvPvm->kohteet->osoite)){
		$osoite = $tvPvm->kohteet->osoite;
	}
	// Osoite -->

	// <-- Tietoja
	if( !empty($tvPvm->tietoja) ){
		echo '<div class="tietoja">';
		echo '<b>'.$osoite.':</b> <br>'.str_replace("\n", "<br>", $tvPvm->tietoja);
		echo '</div>';
		echo '<p style="padding:0;margin:0">------</p>';
	}
	// Tietoja -->
  }
  echo '</td></tr>';
  } // if count > 0
?>
