<?php

    	$color = '';
	$height = '';

	$did = date("Ymd",strtotime($pvm));

	$bod = ''; 
	$bod .=  '<div class="small laatikko latikkoAsetukset" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';

	$bod .=  '
	<div class="pull-right plussamerkki">
	<span class="plussa link glyphicon glyphicon-plus luominen" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
	</div>';

	$bod .=  '
	   <div class="tp">
	     <div class="form-inline">
	     <div class="form-group">
	     </div><div class="form-group">
	   	<i class="forCut" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
	     </div><div class="form-group">
	   	<i class="forCopy" id="forCopy_'.$did.'_'.$tid.'"></i> 
	     </div>
	     </div>
	   </div>';


       	$criteria = new CDbCriteria();
	$criteria->order = " alku ASC";
	$criteria->with=array('kohteet');
	$criteria->condition = " tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' ";
	$tv = Tyovuoroot::model()->findAll($criteria); 
	foreach($tv as $tvVal)
	{

	   $osoite = '';
	   if(isset($tvVal->kohteet->osoite))
	   $osoite = $tvVal->kohteet->osoite;//

	   $strlen = strlen($osoite);
	   $scount = 30;
	   if(isset($tietoja) and $tietoja == 1) $scount = 27;

	   if($strlen > $scount)
	    $osoite = substr($osoite,0,$scount).'..';


	   if($tvVal->alku > 0 and $tvVal->loppu > 0)
	    $al = '<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b><br>';
	   else
	    $al = '';


	 if(!empty($tvVal->tyoajanlaatu) and empty($osoite))
	 {
	    $expl1 = explode("/",$tvVal->tyoajanlaatu);
	    $color = (isset($expl1[1])) ? $expl1[1] : '';
	    $osoite = (isset($expl1[0])) ? $expl1[0] : '';
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


	   $bod .=  '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="fullRivi" style="color:'.$color.'">';
	   if( $from != 'mobiili' )
	   $bod .=  '<span class="link text-danger glyphicon glyphicon-paste muistin" for="'.$tvVal->id.'_'.$did.'_'.$tid.'"></span>';
	   $bod .=  '&nbsp;<span class="link tv_edit" id="tv_'.$tvVal->id.'">'.$al.' '.$osoite.'</span>';

	   if(!empty($tvVal->kohteet->avain))
	   $bod .=  ' <b class="fa fa-key text-warning"></b>';

	   if(!empty($tvVal->tietoja))
	   $bod .=  ' <b class="fa fa-file-text-o text-warning" title="Tietoja"></b>';

	   if(!empty($tvVal->tietoja) and isset($tietoja) and $tietoja == 1)
	   $bod .=  '<p><span style="color: blue; border: 1px #333 solid">'.str_replace("\n","<br>",$tvVal->tietoja).'</span></p>';

	   $bod .=  '<br>
	   </div>';

	}
	$bod .=  '</div>';
/*
	if( $from == 'ajax' )
	{
	$ajax = '';
	$ajax = '<script type="text/javascript" src="'.Yii::app()->request->baseUrl.'/js/tvuoroot.js"></script>';
	$bod .=  $ajax;
	}
*/
	echo json_encode($bod);


?>

