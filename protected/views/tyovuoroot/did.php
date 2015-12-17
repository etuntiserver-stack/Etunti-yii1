<?php

    	$color = '';
	$height = '';

	$did = date("Ymd",strtotime($pvm));
	if(date('N', strtotime($pvm)) == 6 or date('N', strtotime($pvm)) == 7)
	$height = 'style="min-height:10px;"';

	echo '
	   <div class="tp row">
	     <div class="col-sm-1">
	   	<a href="#" class="forCopy" id="forCopy_'.$did.'_'.$tid.'"></a>
	     </div><div class="col-sm-1">
	   	<a href="#" class="forCut" id="forCut_'.$did.'_'.$tid.'"></a>
	     </div>
	   </div>';

	echo '<div '.$height.' class="small laatikko latikkoAsetukset" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';
	

       	$criteria = new CDbCriteria();
	$criteria->order = " alku ASC";
	$criteria->with=array('kohteet');
	$criteria->condition = " tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' ";
	$tv = Tyovuoroot::model()->findAll($criteria); 
	foreach($tv as $tvVal)
	{

	 if(isset($tvVal))
	 {
	   $osoite = $tvVal->kohteet->osoite;
	   $strlen = strlen($tvVal->kohteet->osoite);

	   if($strlen > 35)
	    $osoite = substr($osoite,0,35).'..';
	   else
	    $osoite = $tvVal->kohteet->osoite;

	   if($tvVal->alku > 0 and $tvVal->loppu > 0)
	    $al = $tvVal->alku.'-'.$tvVal->loppu;
	   else
	    $al = '';


	 if(!empty($tvVal->tyoajanlaatu) and empty($k['osoite']))
	 {
	    $expl1 = explode("/",$tvVal->tyoajanlaatu);
	    $color = (isset($expl1[1])) ? $expl1[1] : '';
	    $k['osoite'] = (isset($expl1[0])) ? $expl1[0] : '';
	 } else {
	    $color = '';
	 }

	 if(!empty($tvVal->tyoajanmerkinta))
	 {
	    $expl = explode("/",$tvVal->tyoajanmerkinta);
	    $color = (isset($expl[1])) ? $expl[1] : '';
	 } else {
	    $color = '';
	 }


	   echo '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi" style="color:'.$color.'">';
	   if( $from != 'mobiili' )
	   echo '<a href=# class="text-danger glyphicon glyphicon-paste muistin" for="'.$tvVal->id.'_'.$did.'_'.$tid.'"></a>';
	   echo '&nbsp;<span class="link tv_edit" id="tv_'.$tvVal->id.'">'.$al.' '.$osoite.'</span>';

	   if(!empty($tvVal->kohteet->avain))
	   echo ' <b class="fa fa-key text-warning pull-right"></b>';

	   if(!empty($tvVal->tietoja))
	   echo ' <b class="fa fa-file-text-o text-warning pull-right" title="Tietoja"></b>';

	   echo '<br>
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

