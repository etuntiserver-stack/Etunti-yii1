<?php

	if(isset($yhteensa) and $yhteensa == true)
	$site = Yii::app()->createController('Site');


    	$color = '';
	$height = '';
	$yht = 0;

	$did = date("Ymd",strtotime($pvm));
	$onkoMennyt = '';
	if($did < date("Ymd"))
	$onkoMennyt = 'mennytPaivat';

	$bod = ''; 
	$bod .=  '<div class="small laatikko latikkoAsetukset '.$onkoMennyt.'" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'">';


if(!isset($_POST['tulosta']))
{

	$bod .=  '
	<div class="pull-right oikeallaPlusV">
	  <div class="form-inline">

	<div class="kokopaiva form-group">
	<span class="valitseKokopaiva link glyphicon glyphicon-th-large" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
	</div>

	<div class="plussamerkki form-group">
	<span class="plussa link glyphicon glyphicon-plus luominen" pvm="'.date("d.m.Y",strtotime($pvm)).'" tid="'.$tid.'"></span>
	</div>

	  </div>
	</div>';


	$bod .=  '
	   <div class="tp">
	     <div class="form-inline">
	     <div class="form-group">
		<div class="trash" style="margin-right: 5px"></div>
	     </div><div class="form-group">
		<div class="clear" style="margin-right: 5px"></div>
	     </div><div class="form-group">
	   	<i class="forCut" id="forCut_'.$did.'_'.$tid.'" style="margin-right: 5px"></i>
	     </div><div class="form-group">
	   	<i class="forCopy" id="forCopy_'.$did.'_'.$tid.'"></i> 
	     </div>
	     </div>
	   </div>';
}

       	$criteria = new CDbCriteria();
	$criteria->order = " alku ASC";
	$criteria->with=array('kohteet');
	$criteria->condition = " tid = '".$tid."' and pvm = '".date("d.m.Y",strtotime($pvm))."' ";

	if(isset($kohteenArr) and count($kohteenArr) > 0)
	{
		$kohteenArr = implode(',',$kohteenArr);
		$criteria->addCondition  (" kohde IN ($kohteenArr) ");
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

		}

	   }




	   if($tvVal->alku != '' and $tvVal->loppu != '')
	   {

		if(isset($yhteensa) and $yhteensa == true)
		{
			$eilasketa = $site[0]->eiLasketaSubStr($tvVal->tyoajanmerkinta);
			if($eilasketa != true)
	    		$yht += strtotime($tvVal->loppu)-strtotime($tvVal->alku);
		}

	    if(!isset($_POST['tulosta'])) $br = '<br>'; else $br = '';

	    $al = '<b>'.$tvVal->alku.'-'.$tvVal->loppu.'</b>'.$br;
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

	   $bod .=  '<div id="'.$tvVal->id.'_'.$did.'_'.$tid.'" class="well fullRivi" style="color:'.$color.'">';
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

	if(isset($yhteensa) and $yhteensa == true)
		echo json_encode($bod.'//'.$yht);
	else
		echo json_encode($bod);


?>

