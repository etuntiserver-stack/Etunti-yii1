<?php
$did = date("Ymd",strtotime($pvm));
//echo '<div id="'.$did.'_'.$tid.'">';
	$laatikot = '<div class="small">';

	$muutos = false;
	$tun = 0;
	$get = array();

       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	 "; //AND kid IN (SELECT id FROM sivexkuitti)
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tv = Toteutuneet::model()->findAll($criteria); 

	$u = 0;
	foreach($tv as $tvVal){
	$u++;

	   if($tvVal->id){
	   $muutos = true;

	   $get[strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->kid."//".$tvVal->asiakas_hyvaksy."//".$tvVal->tietoja."//".$tvVal->sairaus;

	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}


       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) ";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$mob = Mobile::model()->findAll($criteria); 
	$u = 0;
	foreach($mob as $tvVal){
	$u++;

	   if($tvVal->id){
	   $muutos = false;

	   $get[strtotime($tvVal->loppui)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->id."//".$tvVal->asiakas_hyvaksy."////".$tvVal->sairaus;


	  $tvVal->loppui = date("Y-m-d H:i",strtotime($tvVal->loppui));
	  $tvVal->aloitan = date("Y-m-d H:i",strtotime($tvVal->aloitan));

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui))
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}

	if( $from == 'kk' ){
		if($tun > 0)
		echo sprint($tun)."//".$tun;

	} else {

		   ksort($get);
		   foreach($get as $v){
		      $laatikot .= $this->renderPartial('al',array('str'=>$v), true);
		   }

	}
	
	$laatikot .= '&nbsp;&nbsp;<b class="link glyphicon glyphicon-plus uusirivi" for="'.$did.'_'.$tid.'"></b>';
	$laatikot .= '</div>';

	if($from != 'kk')
        echo $laatikot.'explode999'.$tun;

/*
	if( $from == 'ajax' ){
	?>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/totrivi_poista.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>
	<?php
	}
*/
?>

