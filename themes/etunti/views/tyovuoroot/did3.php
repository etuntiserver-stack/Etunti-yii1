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
	$bod .=  '
	<div class="latikkolisatiedot_paa">
		<div class="latikkolisatiedot">
		 <div class="form-inline">
			<div class="form-group">
			   <span class="text-center" id="sum_tunnit_'.$did.'_'.$tid.'" style="text-align:center;opacity:0.6"></span>
			</div>
			<div class="kokopaiva form-group">
			   <span class="valitseKokopaiva link glyphicon glyphicon-th-large" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
			</div>
			<div class="plussamerkki form-group">
			   <span class="plussa link fa fa-plus luominen" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
			</div>
			<div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mcut fa fa-exchange link':'forCut').'"" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
		 	</div><div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mplus fa fa-copy link':'forCopy').'" id="forCopy_'.$did.'_'.$tid.'"></i> 
			</div>
		 </div>
		</div>
	</div>
	';

	$bod .=  '<div style="position:relative" class="latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';

       	$criteria = new CDbCriteria();
	$criteria->order = " alku ASC";
	$criteria->condition = " tid = '".$tid."' AND pvm = '".date("d.m.Y",strtotime($pvm))."' ";

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
		$color = '#888';
		$bgcol = 'color:#333';

	   	if($tvVal->alku != '' and $tvVal->loppu != '')
	   	{
			$eilasketa = $this->eiLasketaSubStr($tvVal->tyoajanmerkinta);
			if(isset($asetukset) and $tvVal->status == 10 and $asetukset->lasketaanko_lounastauko == 0)
			{
			} else {
				if($eilasketa != true)
	    			$sum += strtotime($tvVal->loppu)-strtotime($tvVal->alku);
			}
		}
		// <-- Osoite
		$osoite = '';
		if(!empty($tvVal->osoite)){
			$osoite = $tvVal->osoite;
		} elseif(empty($tvVal->osoite) and isset($tvVal->kohteet->osoite)){
			$osoite = $tvVal->kohteet->osoite;
		}
		// Osoite -->

		// <-- Status
		$status = '';
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-success' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-cutlery '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-warning' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-bus '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-hourglass '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="tvikooni fa fa-clock-o '.$teksti_vari.'"></i>';
		}
		// Status -->

		// <-- Asiakas nakyvissa
		$asiakasNakyvissa = '';
		if(isset($asetukset) and $asetukset->asiakas_tyovuorossa == 1){
		$name = '';
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'yritys')
		$name = $tvVal->kohteet->asiakkaat->yrityksen_nimi;
		if(isset($tvVal->kohteet->asiakkaat) and $tvVal->kohteet->asiakkaat->tyyppi == 'henkilo')
		$name = $tvVal->kohteet->asiakkaat->yhteyshenkilo;
		if(!empty($name)){ $asiakasNakyvissa = '<b>Asiakas:</b> '.$name.'<br>'; }
		}
		//  Asiakas nakyvissa -->

		// <-- Paikkakunta nakyvissa
		$paikkakuntaNakyvissa = '';
		if(isset($asetukset) and $asetukset->paikkakunta_tyovuorossa == 1){
		$paikkakunta = '';
		if(isset($tvVal->kohteet->kaupunki) and !empty($tvVal->kohteet->kaupunki))
		$paikkakunta = $tvVal->kohteet->kaupunki;
		if(!empty($paikkakunta)){ $paikkakuntaNakyvissa = '<b>Paikkakunta:</b> '.$paikkakunta.'<br>'; }
		}
		//  Paikkakunta nakyvissa -->

		// <-- Toistuva
		$toistuva = '';
		if($tvVal->toistuva_id != 0){
			$toistuva = ' <i class="tvikooni fa fa-repeat text-success" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
		}
		// Toistuva -->

		// <-- Avaimet
		$avaimet = '';
		if(isset($tvVal->avaimet) and count($tvVal->avaimet) > 0){
			$avaimet =  ' <i class="tvikooni fa fa-key"></i>';
		}
		// Avaimet -->

		// <-- Hovertietoja generoi
		$hovertietoja = '';
		// <-- peruutettu
		if($tvVal->peruutettu != 0){
			$tv_controller = Yii::app()->createController('Tyovuoroot');
		}
		if($tvVal->peruutettu == 1 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $tv_controller[0]->peruutettuArray()[1] .'</h3>';
			$bgcol = 'color:red';
		}
		if($tvVal->peruutettu == 2 and isset($tv_controller)){
			$hovertietoja .= '<h3 class="text-danger">'. $tv_controller[0]->peruutettuArray()[2] .'</h3>';
			$bgcol = 'color:red';
		}
		//    peruutettu -->
		$hovertietoja .= $asiakasNakyvissa;
		//if(!empty($asiakasNakyvissa)){ $title .= ', '; }
		$hovertietoja .= $paikkakuntaNakyvissa;
		$hovertietoja .= '<br><p><span class="didstatus">'.$status.$toistuva.$avaimet.'</span>&nbsp; &nbsp;<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>: '.$osoite.'</p>';
		if( $tvVal->tyopaari != '' and $tvVal->tyopaari != "[\"$tvVal->tid\"]" ){
		$hovertietoja .= '<div class="hover_well"><h5>Työparit</h5>';
		   foreach(json_decode($tvVal->tyopaari, true) as $tyopaari){
			if( $tvVal->tid != $tyopaari )
			$hovertietoja .=  $this->etuSukunimi($tyopaari).'<br>';
		   }
		$hovertietoja .= '</div>';
		}
		if( !empty($tvVal->tietoja) ){ $hovertietoja .= '<div class="hover_well"><h5>Tietoja:</h5> '.$tvVal->tietoja.'</div>'; }
		//    Hovertietoja generoi -->

		if( isset($loppu[0]) and empty($loppu[0]) and isset($loppu[1]) and ($this->num(strtotime($tvVal->alku)-strtotime($loppu[1])) > 0) ){
			$valilyonti = strtotime($tvVal->alku)-strtotime($loppu[1]);
			$reikatyyppi = 'reika-warning';
			$bod .= '<div class="reika '.$reikatyyppi.' text-center"><i class="glyphicon glyphicon-time"></i> Aika: '.$this->sprint($valilyonti).'</div>';
		}

		$tv_edit 	= 'tv_edit';
		$fullRivi 	= 'fullRivi';
	   	$muistin	= 'muistin';

		$kellot = '<b class="kellot">'.$tvVal->alku.'-'.$tvVal->loppu.': </b>';
	        $muokkaus =  '<i class="link tvikooni fa fa-pencil-square-o '.$muistin.'" for="'.$tvVal->id.'_'.$did.'_'.$tid.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Valinta kopiontia tai siirtämistä varten').'"></i>';

		if(!empty($tvVal->tyoajanmerkinta)){
			$expl = explode("/",$tvVal->tyoajanmerkinta);
			if(isset($expl[1]) and !empty($expl[1])){
				$color = $expl[1];
				$bgcol = 'color:'.$color;
			}
		}
		if(!empty($tvVal->tyoajanlaatu) and empty($osoite)){
			$expl1 = explode("/",$tvVal->tyoajanlaatu);
			if(isset($expl1[1]) and !empty($expl1[1])){ $color = $expl1[1]; }
			$osoite = (isset($expl1[0])) ? '<div class="text-center tyoajanlaatu_laatiko" style="background:'.$color.'">'.$expl1[0].'</div>' : '';
			$kellot = '';
			$muokkaus = '';
		}

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

	   	$bod .= '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="'.$fullRivi.'" style="'.$bgcol.'">';
		$bod .= '<div class="pull-left ikoonintila" style="display:none">'.$muokkaus.$status.$toistuva.$avaimet.' </div>';
		$bod .= '<span class="'.$tv_edit.'" id="tv_'.$tvVal->id.'">';
		$bod .= '<div class="hovertietoja" style="display:none">'.$hovertietoja.'</div>';
		$bod .= $kellot.$osoite;
	   	$bod .= '</span>';
	   	$bod .= '</div>';
		$loppu = array($tvVal->tyoajanlaatu, $tvVal->loppu);
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
