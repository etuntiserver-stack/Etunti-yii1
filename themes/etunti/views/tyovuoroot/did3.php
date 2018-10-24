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
	$bod .=  '<div class="latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';


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

		$color = '#333';
		if(!empty($tvVal->tyoajanmerkinta)){
		$expl = explode("/",$tvVal->tyoajanmerkinta);
		if(isset($expl[1]) and !empty($expl[1])) $color = $expl[1];
		}
		$top = sprintf("%0.2f", $this->time_to_float($tvVal->alku));
		$height = $this->num(strtotime($tvVal->loppu)-strtotime($tvVal->alku));
		$kerta = 3;
	   	$bod .=  '
		<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" style="margin-top:'.($top*$kerta).'px;width:60px;height:'.($height*$kerta).'px;background:'.$color.'">
		</div>';

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
