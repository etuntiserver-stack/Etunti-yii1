<?php
$did = date("Ymd",strtotime($pvm));
//echo '<div id="'.$did.'_'.$tid.'">';
	echo '<div class="small">';

	$muutos = false;
	$tun = 0;

       	$criteria = new CDbCriteria();
	$criteria->condition = " tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND kid IN (SELECT id FROM sivexkuitti) ";
	if(Yii::app()->session['Lounastauko'])
	$criteria->addCondition (" status != '10' ");
	if(Yii::app()->session['MATKA'])
	$criteria->addCondition (" status != '2' ");

	$tv = Toteutuneet::model()->findAll($criteria); 

	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $muutos = true;

	   $get[strtotime($tvVal->aloitan)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->kid;

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui) and $from == 'kk')
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

	foreach($mob as $tvVal){
	   if($tvVal->id){
	   $muutos = false;

	   $get[strtotime($tvVal->aloitan)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan))."//".$tvVal->id;

	   if(!empty($tvVal->aloitan) and !empty($tvVal->loppui) and $from == 'kk')
	   $tun += strtotime($tvVal->loppui)-strtotime($tvVal->aloitan);
	   }
	}

	if( $from == 'kk' ){
		if($tun > 0)
		echo sprint($tun)."//".$tun;

	} else {

		if(isset($get) and count($get) > 0)
		{
		   ksort($get);
	
		   foreach($get as $v)
		      $this->renderPartial('al',array('str'=>$v));
	
		} else {
	
		      //$this->renderPartial('al',array('str'=>$get[strtotime($tvVal->aloitan)]));
		}

	}
	echo '</div>';


	if( $from == 'ajax' ){
	?>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>
	<?php
	}
?>

