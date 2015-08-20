<?php


	$did = date("Ymd",strtotime($pvm));

	echo '
	   <div class="tp row">
	     <div class="col-sm-1">
	   	<a href="#" class="forCopy" id="forCopy_'.$did.'_'.$tid.'"></a>
	     </div><div class="col-sm-1">
	   	<a href="#" class="forCut" id="forCut_'.$did.'_'.$tid.'"></a>
	     </div>
	   </div>
	   <div class="small laatikko latikkoAsetukset" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';

	$tv = Tyovuoroot::model()->with('kohteet')->findAll("tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' "); 
	foreach($tv as $tvVal)
	{

	 if(isset($tvVal) and !empty($tvVal->kohde))
	 {
	   $k = Kohteet::model()->findbypk($tvVal->kohde,array("select"=>"osoite"));
	   $strlen = strlen($k['osoite']);

	   if($strlen > 18)
	    $k['osoite'] = substr($k['osoite'],0,18).'..';
	   else
	    $k['osoite'] = $k['osoite'];

	   if($tvVal->alku > 0 and $tvVal->loppu > 0)
	    $al = $tvVal->alku.'-'.$tvVal->loppu;
	   else
	    $al = '';

	   echo '
	   <div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi">';
	   if( $from != 'mobiili' )
	   echo '<a href=# class="text-danger glyphicon glyphicon-paste" for="'.$tvVal->id.'_'.$did.'_'.$tid.'"></a>';
	   echo '&nbsp;<a href="#" class="link tv_edit" id="tv_'.$tvVal->id.'">'.$al.' '.$k['osoite'].'</a><br>
	   </div>';

	 }
	}

	echo '</div>';

	if( $from == 'ajax' ){
	?>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tvuoroot.js"></script>
	<?php
	}

//print_r($tvVal->kohde);
//exit;
?>

