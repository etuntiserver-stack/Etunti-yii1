<?php
$did = date("Ymd",strtotime($pvm));
//echo '<div id="'.$did.'_'.$tid.'">';
	echo '<div class="small">';

	$muutos = false;

	$tv = Toteutuneet::model()->findAll("tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti) "); 

	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $muutos = true;
	   $get[strtotime($tvVal->aloitan)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

	$tv = Sivexkuitti::model()->findAll("tid = '".$tid."' 
	and DATE_FORMAT(STR_TO_DATE(aloitan, '%d.%m.%Y'), '%Y-%m-%d') = '".date("Y-m-d",strtotime($pvm))."' 
	AND id NOT IN (SELECT kid FROM sivexkuitti_repaired) "); 

	foreach($tv as $tvVal){
	   if($tvVal->id){
	   $muutos = false;
	   $get[strtotime($tvVal->aloitan)] = $tvVal->id."//".$tvVal->aloitan."//".$tvVal->loppui."//".$tvVal->kohde_kannasta."//".$did."//".$tid."//".$muutos."//".(strtotime($tvVal->loppui)-strtotime($tvVal->aloitan));
	   }
	}

	if(isset($get) and count($get) > 1)
	{
	   ksort($get);

	   foreach($get as $v)
	      $this->renderPartial('al',array('str'=>$v));

	} /*else {

	      $this->renderPartial('al',array('str'=>$get[strtotime($tvVal->aloitan)]));
	}*/

	echo '</div>';


	if( $from == 'ajax' ){
	?>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/toteuma.js"></script>
	<?php
	}
?>

