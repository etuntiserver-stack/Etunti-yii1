<?php
    	$color = '';
	$height = '';
	$yht = 0;
	$sum = 0;

	$did = date("Ymd",strtotime($pvm));
	$onkoMennyt = '';
	if($did < date("Ymd"))
	$onkoMennyt = 'mennytPaivat';

	$bod = ''; 
	$bod .=  '<div class="laatikko latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';

if(!isset($_POST['tulosta']))
{

	if( $from != 'mobiili' )
	{
	$bod .=  '
	<div class="ylapalkki">
	<div class="pull-right">
	    <span class="text-center text-danger" id="sum_tunnit_'.$did.'_'.$tid.'"></span>
	</div>

	<div class="showhing oikeallaPlusV"  style="display:none">
	 <div class="form-inline">
	  <div class="kokopaiva form-group">
	   <i class="valitseKokopaiva link text-danger fa fa-th-large icon" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></i>
	  </div>
	  <div class="plussamerkki form-group">
	   <i class="plussa link text-danger fa fa-plus luominen icon" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></i>
	   <i class="showhing link text-danger fa fa-arrow-down naytacollapse icon" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></i>
	  </div>
	 </div>
	</div>
	</div>';
	}


	$bod .=  '
	   <div class="tp">
	     <div class="form-inline">
	      <div class="form-group">
	   	<i class="forCut icon" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
	      </div><div class="form-group">
	   	<i class="forCopy icon" id="forCopy_'.$did.'_'.$tid.'"></i> 
	      </div>
	     </div>
	   </div>';
}

       	$criteria = new CDbCriteria();
	$criteria->order = " alku ASC";
	$criteria->with=array('kohteet');
	$criteria->condition = " tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' ";

	// <-- Asiakas
	if(isset($asiakas) and !empty($asiakas))
	{
		$criteria->addCondition ("
		kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE asiakas_id IN
   			    (
			       SELECT id FROM asiakkaat WHERE 
				yrityksen_nimi LIKE '%".$asiakas."%' 
				OR yhteyshenkilo LIKE '%".$asiakas."%' 
				OR puhelin LIKE '%".$asiakas."%'
			    )
		       )
		   ");
	}
	// Asiakas -->

	// <-- Kohde
	if(isset($kohde) and !empty($kohde))
	{
	           $criteria->addCondition ("
		   kohde IN 
		       (
			    SELECT id FROM sivex_kohdet WHERE 
				osoite LIKE '%".$kohde."%' 
				OR puh_nro LIKE '%".$kohde."%'
		       )
		   ");
	}
	// Kohde -->


	if(isset($kohteet_siivous) and count($kohteet_siivous) > 0)
	{
		$impl = implode(',',$kohteet_siivous);
		$criteria->addCondition  (" kohde IN ($impl) ");
	}

	$tv = Tyovuoroot::model()->findAll($criteria); 
	foreach($tv as $tvVal)
	{
		// <-- poistetaan se 2019 vuodessa
		if( empty($tvVal->osoite) and isset($tvVal->kohteet->osoite) ){ 
			Tyovuoroot::model()->updateByPk($tvVal->id, array('osoite' => $tvVal->kohteet->osoite, 'postinumero' => $tvVal->kohteet->pnumero, 'postitoimipaikka' => $tvVal->kohteet->kaupunki));
			$tvVal->osoite = $tvVal->kohteet->osoite;
		}
		//     poistetaan se 2019 vuodessa -->


	   $osoite = '';
	   if(isset($tvVal->kohteet->osoite) and empty($tvVal->osoiteOnline) and $tvVal->onlinevaraus_id == 0)
	   {

	   	$strlen = strlen($osoite);
	   	$scount = 30;
	   	if(isset($tietoja) and $tietoja == 1) $scount = 27;

	   	if($strlen > $scount)
	    	$osoite = substr($osoite,0,$scount).'..';

	   	$osoite = str_replace('/', '', $tvVal->osoite);

	   } elseif(!empty($tvVal->osoiteOnline) and $tvVal->osoiteOnline == 1 and $tvVal->onlinevaraus_id == 0){

	   	$osoite = '<span style="color: red">Vuoroa varataan..</span>';

	   } elseif(!empty($tvVal->osoiteOnline) and $tvVal->onlinevaraus_id != 0){

		$ov = Onlinevaraus::model()->findbypk($tvVal->onlinevaraus_id);

		if(isset($ov->id))
		{

			$osoite = $ov->osoite;
	   		$strlen = strlen($osoite);
		   	$scount = 30;
		   	if(!empty($ov->lisatietoja)) $scount = 27;
	
		   	if($strlen > $scount)
		    	$osoite = substr($osoite,0,$scount).'..';
	
		   	$osoite = str_replace('/', '', $ov->osoite);

			if($tvVal->osoiteOnline == 1)
		   	$osoite = '<span style="color: red">Vuoroa varataan..<br>'.$osoite.'</span>';
			elseif($tvVal->osoiteOnline == 2)
	   		$osoite = $osoite.'<br><span style="color: green">Onlinevaraus maksettu</span>';
			elseif($tvVal->osoiteOnline == 3)
	   		$osoite = $osoite.'<br><span style="color: green">Tilaus eDicosta</span>';

		}

	   } 

	   // <-- uusi_tilaus
	   $uusi_tilaus = '';
	   /*
	   if( isset($tvVal->kohteet->uusi_tilaus) and $tvVal->kohteet->uusi_tilaus == 1 ){
	   	$uusi_tilaus = 'color:#ff1aff';
	   }
	   */
	   // uusi_tilaus -->

	   // Asiakas nakyvissa
	   $asiakasNakyvissa = '';
	   if(isset($asetukset) and $asetukset->asiakas_tyovuorossa == 1){
		$name = '';
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'yritys')
		$name = $tvVal->kohteet->asiakkaat->yrityksen_nimi;
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'henkilo')
		$name = $tvVal->kohteet->asiakkaat->yhteyshenkilo;

		if(!empty($name))
		$asiakasNakyvissa = $name.'<br>';
	   }

	   // paikkakunta nakyvissa
	   $paikkakuntaNakyvissa = '';
	   if(isset($asetukset) and $asetukset->paikkakunta_tyovuorossa == 1){
		$paikkakunta = '';
		if(isset($tvVal->kohteet->kaupunki) and !empty($tvVal->kohteet->kaupunki))
		$paikkakunta = $tvVal->kohteet->kaupunki;

		if(!empty($paikkakunta))
		$paikkakuntaNakyvissa = $paikkakunta.'<br>';
	   }

	   // toistuva
	   $toistuva = '';
	   if($tvVal->toistuva_id != 0){
		$toistuva = '<i class="p5 fa fa-repeat text-danger icon" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
	   }

	   // peruutettu
	   $peruutettu = '';

	   if($tvVal->peruutettu != 0){
		   $tv_controller = Yii::app()->createController('Tyovuoroot');
	   }	

	   if($tvVal->peruutettu == 1 and isset($tv_controller)){
		$peruutettu = '<p><span class="text-danger">'. $tv_controller[0]->peruutettuArray()[1] .'</span></p>';
	   }
	   if($tvVal->peruutettu == 2 and isset($tv_controller)){
		$peruutettu = '<p><span class="text-danger">'. $tv_controller[0]->peruutettuArray()[2] .'</span></p>';
	   }

	   // Tyopari
	   $tyopari = '';
	   if($tvVal->tyopaari != '' and $tvVal->tyopaari != "[\"$tvVal->tid\"]"){
		$tyopari = '<i class="p5 fa fa-male text-danger icon" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Työpari').'"></i>';
	   }

	   // <-- Tarvittavien työntekijöiden määrä
	   $tarvittavien_tyontekijoiden_maara = '';
	   if(isset($tvVal->kohteet->tarvittavien_tyontekijoiden_maara) and $tvVal->kohteet->tarvittavien_tyontekijoiden_maara > 0)
	   $tarvittavien_tyontekijoiden_maara = '<i class="p3 pull-right text-danger icon">'.$tvVal->kohteet->tarvittavien_tyontekijoiden_maara.'</i>';
	   // Tarvittavien työntekijöiden määrä -->

	   // status
	   $status = '';
	   if($tvVal->status != 0){

		$tvController = Yii::app()->createController('Tyovuoroot');

        	$l = $tvController[0]->tilanteet();
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-danger' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-cutlery '.$teksti_vari.' icon"></i>';
		} elseif($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-danger' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-bus '.$teksti_vari.' icon"></i>';
		} elseif($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-danger' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-hourglass '.$teksti_vari.' icon"></i>';
		} elseif($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-danger' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-clock-o '.$teksti_vari.' icon"></i>';
		} else {
	   		$status = ' ('.$l[$tvVal['status']].') ';
		}
	   }


	   if($tvVal->alku != '' and $tvVal->loppu != '')
	   {

		$eilasketa = $this->eiLasketaSubStr($tvVal->tyoajanmerkinta);

		if(isset($asetukset) and $tvVal->status == 10 and $asetukset->lasketaanko_lounastauko == 0)
		{
		} else {
			if($eilasketa != true)
    			$sum += strtotime($tvVal->loppu)-strtotime($tvVal->alku);
		}

		if(isset($yhteensa) and $yhteensa == true)
		{
			if($eilasketa != true)
	    		$yht += strtotime($tvVal->loppu)-strtotime($tvVal->alku);
		}

		$al = '<span class="pull-right tv_kesto hidden">'.$this->num(strtotime($tvVal->loppu)-strtotime($tvVal->alku)).'</span>';
	   	$al .= '<b>'
			.$tvVal->alku.'-'.$tvVal->loppu.'</b>';

	   } else {
	   	$al = '';
	   }

	   $color = '';
	   if(!empty($tvVal->tyoajanmerkinta))
	   {
	    $expl = explode("/",$tvVal->tyoajanmerkinta);
	    if(isset($expl[1]) and !empty($expl[1])) $color = $expl[1];

	   } 
	   if(!empty($tvVal->tyoajanlaatu) and empty($osoite)){
	    $expl1 = explode("/",$tvVal->tyoajanlaatu);
	    if(isset($expl1[1]) and !empty($expl1[1])) $color = $expl1[1];
	    $osoite = (isset($expl1[0])) ? $expl1[0] : '';
  	   } 

	   $avaimet = '';
	   if(isset($tvVal->avaimet) and count($tvVal->avaimet) > 0){$avaimet =  ' <i class="fa fa-key text-danger icon"></i>';}
	   $tietoja_ic = '';
	   if(!empty($tvVal->tietoja)){$tietoja_ic =  ' <i class="fa fa-file-text-o text-danger icon" title="Tietoja"></i>';}

	   $tv_edit 	= 'tv_edit';
	   $fullRivi 	= 'fullRivi';
	   $muistin	= 'muistin';

	// <-- Tyoryhmat
	$site = Yii::app()->createController('Site');
	if( 
	   isset($tvVal->kohteet) 
	   and isset($asetukset) 
	   and $asetukset->tyoryhmat_kohde == 1 
	){
		$arr = $site[0]->TyoryhmatHelper();
		if( count($arr) > 0 and !in_array($tvVal->kohteet->tyoryhma, $arr)){
   			$tv_edit 	= '';
   			$fullRivi 	= 'fullRivi bg-danger ei_saa_muokata';
			$muistin	= '';
		}
	}
   	//    Tyoryhmat -->

	   $siirto = '<i class="link text-danger fa fa-exchange '.$muistin.' icon" for="'.$tvVal->id.'_'.$did.'_'.$tid.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Valinta kopiontia tai siirtämistä varten').'"></i>';

	   if(!empty($osoite)){ $osoite = $asiakasNakyvissa.$paikkakuntaNakyvissa.$osoite; }
	   $bod .=  '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="did '.$fullRivi.'" style="color:'.$color.'">';

	   $bod .=  '
	   <div class="row">
	    <div class="col-sm-12">
		<div class="collapse">'.$siirto.$toistuva.$tyopari.$tarvittavien_tyontekijoiden_maara.$avaimet.$tietoja_ic.'<br></div>
		<span class="link '.$tv_edit.'" id="tv_'.$tvVal->id.'" style="'.$uusi_tilaus.'">
		<span class="collapse">'.$al.'<br></span>'.$osoite.'<span class="pull-right">'.$status.'</span></span>
	    </div>
	   </div>
	   ';

	   if(!empty($tvVal->tietoja) and isset($tietoja) and $tietoja == 1)
	   $bod .=  '<p><span style="color: blue; border: 1px #333 solid">'.str_replace("\n","<br>",$tvVal->tietoja).'</span></p>';

	   $bod .= $peruutettu;
	   $bod .=  '</div>';
	}
	$bod .=  '</div>';

	if($sum > 0){
	$bod .= '
	<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function(){ $("#sum_tunnit_'.$did.'_'.$tid.'").html("'.$this->sprint($sum).'"); }, 500);
	});
	</script>';
	}



	if(isset($yhteensa) and $yhteensa == true){
		echo json_encode($bod.'//'.$yht);
	} else {
		echo json_encode($bod);
	}


?>
