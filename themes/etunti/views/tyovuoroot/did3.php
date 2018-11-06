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
			   <span class="link fa fa-eye katsokaikki" for="'.$did.'_'.$tid.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'" from="tvuoro" kohteet_siivous="'.json_encode($kohteet_siivous).'" asiakas="'.$asiakas.'" kohde="'.$kohde.'" etusukunimi="'.$this->etuSukunimi($tid).'"></span>
			</div>
			<div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mcut glyphicon glyphicon-transfer text-success link':'forCut').'"" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
		 	</div><div class="form-group">
		   	   <i class="'.((isset($_SESSION['muistin']) and count($_SESSION['muistin']) > 0)?'mplus glyphicon glyphicon-plus text-success link':'forCopy').'" id="forCopy_'.$did.'_'.$tid.'"></i> 
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
		if(isset($loppu) and strtotime($tvVal->alku) > $loppu){
			$valilyonti = $this->num(strtotime($tvVal->alku)-strtotime($loppu));
			$bod .= '<div style="cursor: pointer;background:orange;height:'.($valilyonti*17).'px" title="Aika: '.$this->sprint(strtotime($tvVal->alku)-strtotime($loppu)).'"></div>';
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
	   	$bod .=  '
		<div class="tvline tv_edit" id="tv_'.$tvVal->id.'" style="'.$bgcol.'">
		<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>: '.((isset($tvVal->kohteet->osoite))?$tvVal->kohteet->osoite:'').'
		</div>';
		$loppu = $tvVal->loppu;
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
