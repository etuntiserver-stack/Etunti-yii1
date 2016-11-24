<?php
/*
$did = date("Ymd",strtotime($pvm));
//echo '<div id="'.$did.'_'.$tid.'">';
	$laatikot = '<div class="small">';

	$muutos = false;
	$tun = 0;
	$get = array();

       	$criteria = new CDbCriteria();
	$criteria->condition = " 
		tid = '".$tid."' 
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	 ";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tv = Toteutuneet::model()->findAll($criteria); 
	foreach($tv as $tvVal){

	   if($tvVal->id){
	   $muutos = true;

	   $get[strtotime($tvVal->aloitan)+strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->kid."//".$tvVal->asiakas_hyvaksy."//".$tvVal->tietoja."//".$tvVal->sairaus;

	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}


       	$criteria = new CDbCriteria();
	$criteria->condition = " 
		tid = '".$tid."' 
		AND DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
		AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) 
	";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$mob = Mobile::model()->findAll($criteria); 
	foreach($mob as $tvVal){

	   if($tvVal->id){
	   $muutos = false;

	   $get[strtotime($tvVal->aloitan)+strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->id."//".$tvVal->asiakas_hyvaksy."////".$tvVal->sairaus;


	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}


		   ksort($get);
		   foreach($get as $v){
		      $laatikot .= $this->renderPartial('al',array('str'=>$v), true);
		   }


	//}
	
	$laatikot .= '&nbsp;&nbsp;<b class="link glyphicon glyphicon-plus uusirivi" for="'.$did.'_'.$tid.'"></b>';
	$laatikot .= '</div>';



	$yhtIlta= 0;
	$yhtYo 	= 0;
	$yhtSu 	= 0;

	$return 	= $this->IltaYoSu($tid,$pvm);

	if(isset($return[0])){
	    $tyoIlta 	= $return[0];
	}
	if(isset($return[1])){
	    $tyoYo 	= $return[1];
	}
	if(isset($return[2])){
	    $tyoSu 	= $return[2];
	}

	$arr = array($laatikot,$tun,$tyoIlta,$tyoYo,$tyoSu);
        return $arr;

*/
?>

