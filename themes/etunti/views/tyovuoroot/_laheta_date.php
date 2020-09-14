<?php
  if( !isset($tyosuhteet_checker[$tid]) and isset($ts->loppu) and !empty($ts->loppu) and strtotime($ts->loppu) < strtotime($date) )
  {
	$tyosuhteet_checker[$tid] = array('nimi' => $this->etuSukunimi($tid), 'tsloppu' => $ts->loppu );
  }

  echo '<tr><td colspan="2"><h2><b>'.$paivat[date('N',$d)].' '.$date.'</b></h2></td></tr>';
  echo '<tr><td valign="top" style="width:390px; vertical-align: top"><h3>Aika/Kohde</h3>';

  $yht = 0;
  foreach($date_arr as $arr){
	$this_id = 0;
	foreach($arr as $v2){
		if( isset($v2['this_id']) ){
			$t =  (object)$v2['data'];
			$this_id = $v2['this_id'];
		}
	}
	if(!isset($t->alku))
		continue;
	if(isset($_POST['pdf_email']) and $this_id > 0){

		$get_id 	= $this->this_id($this_id);
		$model 		= $get_id['model'];
		$toistuva 	= $get_id['toistuva'];
		if(!$toistuva){
			Tyovuoroot::model()->updateByPk($model->id, ['piilota_mobiilista'=>'0']);
		} else {
			// Poistetaan PVM per henkilö toistuvasta ketjusta
			// Tilalle Tavallinen työvuoro
			if( $model['piilota_mobiilista'] == 1 ){
				$model['piilota_mobiilista'] = 0;
				$this->newTvFromToistuva($model, $get_id['pvm'], $get_id['tid'], 'ByLahetysOnPiilotaMobiilistaChanger');
			}
		}
	}

	// <-- Asiakas Tiedot
	$asiakasTiedot = '';
	if(isset($t->kohteet->asiakas_id)){
		$as = Asiakkaat::model()->findbypk($t->kohteet->asiakas_id);
		if(isset($as->yrityksen_nimi) and !empty($as->yrityksen_nimi) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yrityksen_nimi;
		} else if(isset($as->yhteyshenkilo) and empty($as->yrityksen_nimi) and !empty($as->yhteyshenkilo) and $asetukset->tyovuorolahetys_naytetaanko_asiakas == 1){
			$asiakasTiedot = '<b>'.Yii::t('main', 'Asiakas').':</b> '.$as->yhteyshenkilo;
		}
		if($asetukset->tyovuorolahetys_naytetaanko_kohteen_postitoimipaikka == 1){
			$asiakasTiedot .= '<br><b>'.Yii::t('main', 'Kohteen postitoimipaikka').':</b> '.$t->kohteet->kaupunki;
		}
	}
	// Asiakas Tiedot -->

	$al = '';
	if($t->alku > 0 and $t->loppu > 0){
	  $al = $t->alku.'-'.$t->loppu.' (Kesto: '.$this->sprint(strtotime($t->loppu)-strtotime($t->alku)).')';
	  if($site[0]->eiLasketaSubStr($t->tyoajanmerkinta) === false){ $yht += strtotime($t->loppu)-strtotime($t->alku); }
	}

	// <-- osoite
	$osoite = '';
	if(!empty($t->osoite)){
		$osoite = $t->osoite;
	} elseif(isset($t->kohteet->id) and empty($t->osoite)){
		$osoite = $t->kohteet->osoite;
	} elseif($t->status != 3){
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

	echo '<h4 style="margin:0; padding:0">'.$al.' '.$osoite.'</h4>';
	echo $asiakasTiedot;
	// <-- Avain
	if( isset($t->avaimet) and count($t->avaimet) > 0 ){
		echo '<p><b>Avaimet: </b><br>';
		foreach($t->avaimet as $avain){
			echo '&nbsp;&nbsp;&nbsp;'.$avain->avainnumero.':'.$this->etuSukunimi($avain->tid).':'.$avain->sijainti.'<br>';
		}
		echo '</p>';
	}
	//     Avain -->
	// <-- Tyoparit
	if( is_array(json_decode($t->tyopaari, true)) ){
		echo '<p><b>Työparit: </b><br>';
		foreach(json_decode($t->tyopaari, true) as  $id => $tp_id){
			if($tp_id != $t->tid){ echo '&nbsp;&nbsp;&nbsp;'.$this->etuSukunimi($tp_id).'<br>'; }
		}
		echo '</p>';
	}
	//     Tyoparit -->

  }
  if($yht > 0)
  echo '<h4>'.Yii::t('main','Yhteensä: ').$this->sprint($yht).'</h4>';
  echo '</td><td valign="top" style="width:400px;border-left: 1px #ccc solid; vertical-align: top"><h3>Tietoja</h3>';

  foreach($date_arr as $arr){

	foreach($arr as $v2)
		if( isset($v2['this_id']) )
			$t =  (object)$v2['data'];
	//if(!isset($t->osoite))
		//continue;

	// <-- Tietoja
	if( !empty($t->tietoja) ){
		// <-- osoite
		$osoite = '';
		if(!empty($t->osoite)){
			$osoite = $t->osoite;
		} elseif(isset($t->kohteet->id) and empty($t->osoite)){
			$osoite = $t->kohteet->osoite;
		} elseif($t->status != 3){
			$osoite = $this->tilanteet()[$t->status];
		}
		echo '<p><b>'.$osoite.':</b> <br>'.str_replace("\n", "<br>", $t->tietoja).'</p>';
	}
	// Tietoja -->
  }
  echo '</td></tr>';
?>
