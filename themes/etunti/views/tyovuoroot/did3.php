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
	$bod .=  '<div class="small laatikko latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';


if(!isset($_POST['tulosta']))
{

	if( $from != 'mobiili' )
	{
	$bod .=  '
	<div class="pull-right oikeallaPlusV">
	  <div class="form-inline">
	<div class="form-group">
	   <span class="text-center" id="sum_tunnit_'.$did.'_'.$tid.'" style="text-align:center;opacity:0.6"></span>
	</div>
	<div class="kokopaiva form-group">
	   <span class="valitseKokopaiva link glyphicon glyphicon-th-large" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
	</div>
	<div class="plussamerkki form-group">
	<span class="plussa link glyphicon glyphicon-plus luominen" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
	</div>

	  </div>
	</div>';
	}


	$bod .=  '
	   <div class="tp">
	     <div class="form-inline">
	      <div class="form-group">
	   	<i class="forCut" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
	      </div><div class="form-group">
	   	<i class="forCopy" id="forCopy_'.$did.'_'.$tid.'"></i> 
	      </div>
	     </div>
	   </div>';
}

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

	   $osoite = '';
	   if(isset($tvVal->kohteet->osoite) and empty($tvVal->osoiteOnline) and $tvVal->onlinevaraus_id == 0)
	   {

	   	$strlen = strlen($osoite);
	   	$scount = 30;
	   	if(isset($tietoja) and $tietoja == 1) $scount = 27;

	   	if($strlen > $scount)
	    	$osoite = substr($osoite,0,$scount).'..';

	   	$osoite = str_replace('/', '', $tvVal->kohteet->osoite);

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

	   // toistuva
	   $toistuva = '';
	   if($tvVal->toistuva_id != 0){
		$toistuva = '<i class="p5 fa fa-repeat text-success" style="font-size:120%" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Toistuva työvuoro').'"></i>';
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
		$tyopari = '<i class="p5 fa fa-male text-success" style="font-size:120%" data-toggle="tooltip" data-placement="top" title="'. Yii::t('main', 'Työpari').'"></i>';
	   }

	   // <-- Tarvittavien työntekijöiden määrä
	   $tarvittavien_tyontekijoiden_maara = '';
	   if(isset($tvVal->kohteet->tarvittavien_tyontekijoiden_maara) and $tvVal->kohteet->tarvittavien_tyontekijoiden_maara > 0)
	   $tarvittavien_tyontekijoiden_maara = '<i class="p3 pull-right text-success">'.$tvVal->kohteet->tarvittavien_tyontekijoiden_maara.'</i>';
	   // Tarvittavien työntekijöiden määrä -->

	   // status
	   $status = '';
	   if($tvVal->status != 0){

		$tvController = Yii::app()->createController('Tyovuoroot');

        	$l = $tvController[0]->tilanteet();
		if($tvVal->status == 10){
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-success' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-cutlery '.$teksti_vari.'"></i>';
		} elseif($tvVal->status == 2) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-warning' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-bus '.$teksti_vari.'"></i>';
		} elseif($tvVal->status == 3) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-hourglass '.$teksti_vari.'"></i>';
		} elseif($tvVal->status == 11) {
			($tvVal->piilota_mobiilista == 0)? $teksti_vari = 'text-info' : $teksti_vari = 'text-danger';
			$status = ' <i class="p3 fa fa-clock-o '.$teksti_vari.'"></i>';
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

	   if(!empty($osoite)){ $osoite = '<br>'.$osoite; }
	   $bod .=  '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="did '.$fullRivi.'" style="color:'.$color.'">';
	   if( $from != 'mobiili' ){
	       $bod .=  '<span class="link text-danger fa fa-pencil-square-o '.$muistin.'" for="'.$tvVal->id.'_'.$did.'_'.$tid.'" data-toggle="tooltip" data-placement="top" title="'.Yii::t('main', 'Valinta kopiontia tai siirtämistä varten').'"></span>';
	   }

	   $bod .=  '&nbsp;<span class="link '.$tv_edit.'" id="tv_'.$tvVal->id.'">'.$al.' '.$osoite.'</span>';

	   $bod .= $peruutettu;
	   //if($tvVal->toistuva_id != 0) // piilotetaan
	   //$bod .=  '<br><span class="text-warning">Tid: '.$tvVal->tid.', '.$tvVal->id.'. Ketjun numero:'.$tvVal->toistuva_id.'</span> '; // piilotetaan

	   $bod .=  '<br>
	   </div>';

	}
	$bod .=  '</div>';
/*
	if($sum > 0){
	$bod .= '
	<script type="text/javascript">
	$(document).ready(function(){
		setTimeout(function(){ $("#sum_tunnit_'.$did.'_'.$tid.'").html("'.$this->sprint($sum).'"); }, 500);
	});
	</script>';
	}
*/


	if(isset($yhteensa) and $yhteensa == true){
		echo json_encode($bod.'//'.$yht);
	} else {
		echo json_encode($bod);
	}


?>
