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
		// <-- Osoite
		$osoite = '';
		if(!empty($tvVal->osoite)){
			$osoite = $tvVal->osoite;
		} else if(empty($tvVal->osoite) and isset($tvVal->kohteet->osoite)){
			$osoite = $tvVal->kohteet->osoite;
		}
		// Osoite -->

		// <-- Status
		$status = '';
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-success' : $teksti_vari = 'text-danger';
			$status = ' <i class="fa fa-cutlery '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-warning' : $teksti_vari = 'text-danger';
			$status = ' <i class="fa fa-bus '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="fa fa-hourglass '.$teksti_vari.'"></i>';
		}
		if($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="fa fa-clock-o '.$teksti_vari.'"></i>';
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

		// <-- Title generoi
		$title = '';
		$title .= $asiakasNakyvissa;
		//if(!empty($asiakasNakyvissa)){ $title .= ', '; }
		$title .= $paikkakuntaNakyvissa;
		if( !empty($tvVal->tietoja) ){ $title .= '<p><b>Tietoja:</b> '.$tvVal->tietoja.'<p>'; }
		//    Title generoi -->

		// <-- Toistuva
		$toistuva = '';
		if($tvVal->toistuva_id != 0){
			$toistuva = ' <i class="fa fa-repeat text-orange" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
		}
		// Toistuva -->

		if( isset($loppu) and ($this->num(strtotime($tvVal->alku)-strtotime($loppu)) > 0) ){
			$valilyonti = strtotime($tvVal->alku)-strtotime($loppu);
			//$bod .= '<div class="ajanreika" style="height:'.($this->num($valilyonti)*17).'px" data-toggle="tooltip" data-placement="top" title="Aika: '.$this->sprint(strtotime($tvVal->alku)-strtotime($loppu)).'"></div>';
			$reikatyyppi = 'reika-success';
			if( $this->num($valilyonti) > 1 ){ $reikatyyppi = 'reika-warning'; }
			$bod .= '<div class="row reika '.$reikatyyppi.' text-center"><i class="glyphicon glyphicon-time"></i> Aika: '.$this->sprint($valilyonti).'</div>';
		}
		$color = '#888';
		$bgcol = 'color:#333';
		if(!empty($tvVal->tyoajanmerkinta)){
			$expl = explode("/",$tvVal->tyoajanmerkinta);
			if(isset($expl[1]) and !empty($expl[1])){
				$color = $expl[1];
				$bgcol = 'color:'.$color;
			}
		}

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
	        $muokkaus =  '<i class="link fa fa-pencil-square-o '.$muistin.'" for="'.$tvVal->id.'_'.$did.'_'.$tid.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Valinta kopiontia tai siirtämistä varten').'"></i>';

	   	$bod .=  '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="'.$fullRivi.'" style="'.$bgcol.'">';
		$bod .= $muokkaus.$status.$toistuva;
		$bod .= '<span class="'.$tv_edit.'" id="tv_'.$tvVal->id.'" hovertietoja="'.$title.'">';
		$bod .= '<b class="kellot">'.$tvVal->alku.'-'.$tvVal->loppu.': </b>'.$osoite;
	   	$bod .=  '</span>';
	   	$bod .=  '</div>';
		$loppu = $tvVal->loppu;
	}
	$bod .=  '</div>';

	if(isset($yhteensa) and $yhteensa == true){
		echo json_encode($bod.'//'.$yht);
	} else {
		echo json_encode($bod);
	}
?>
