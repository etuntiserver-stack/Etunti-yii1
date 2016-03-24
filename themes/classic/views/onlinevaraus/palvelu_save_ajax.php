<?php

  if(isset($_SESSION['onlinevaraus']['paapalvelu']))
	$model = OnlinevarausTuotteet::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);

  $blockAika = '';
  if(isset($_SESSION['onlinevaraus']['modelTV']))
  {
	$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);
	if(isset($tv->id))
	{

	   $filename = "../../img/tekijat/".$_SESSION['domain']."/".$tv->tid.".jpg";
	   if (file_exists(Yii::app()->request->baseUrl."img/tekijat/".Yii::app()->user->domain."/".$tv->tid.".jpg"))
	   $kuva = '<img src="'.$filename.'" class="img-thumbnail">';
	   else
	   $kuva = '<img src="../../img/tekijat/noname.jpg" class="img-thumbnail">';


	$blockAika = '
	<hr>
     	<label>Varattu aika </label><br>
	<div class="row">
	 <div class="col-sm-4">
		'.$kuva.'		
	 </div><div class="col-sm-8">
		'.$tv->pvm.'<br>
		'.$tv->alku.'-'.$tv->loppu.'
	 </div>
	</div>
 	';
	}
  }

  $blockKohde = '';
  if(isset($_SESSION['onlinevaraus']['modelKohde']))
  {
	$k = Kohteet::model()->findbypk($_SESSION['onlinevaraus']['modelKohde']);
	if(isset($tv->id))
	{
	$blockKohde = '
	<hr>
     	<label>Osoite </label><br>
	<div class="row">
	 <div class="col-sm-4">
		Asiakas: '.$k->asiakas_id.'		
	 </div><div class="col-sm-8">
		'.$k->osoite.'
	 </div>
	</div>
 	';
	}
  }




  if(isset($model))
  {
	$lisaHinta = 0;
	$lisaTunti = 0;

	$body = 
	'
	<div class = "panel panel-success">
	   <div class = "panel-heading">
	      Yhteenveto
	   </div>
	   
	   <div class = "panel-body">


	<div class="row">
	 <div class="col-sm-1">
	   <i class="fa fa-home"></i> 
	 </div><div class="col-sm-11">
		<span id="nimikejanelio">'.$model->nimike.' '.$model->nelio.' m²</span>';

		if(isset($_SESSION['onlinevaraus']['lisapalvelut']))
		foreach($_SESSION['onlinevaraus']['lisapalvelut'] as $p)
		{
		    $onlineTuotteet = OnlinevarausTuotteet::model()->findbypk($p);
		    if(isset($onlineTuotteet->id))
		    {
			$lisaHinta += (float)$onlineTuotteet->hinta;
			$lisaTunti += (float)$onlineTuotteet->kesto;
			$body .= '
			<div class="row">
			 + '.$onlineTuotteet->nimike.' <span style="opacity:0.6">'.$onlineTuotteet->kesto.'</span>
			<span style="opacity:0.6">h</span></span>
			</div>
			';
		    }
		}

	$body .= '
	 </div>
	</div>
	<div class="row">
	 <div class="col-sm-1">
	   <i class="fa fa-clock-o"></i> 
	 </div><div class="col-sm-11">';

	$sumTunti = $lisaTunti+$model->kesto;
	$_SESSION['onlinevaraus']['sumTunti'] = $sumTunti;
	$body .= '<span id="clock">'.number_format($sumTunti, 1, '.', '').'</span> tuntia';

	$body .= '
	 </div>
	</div>
	<div class="row">
	 <div class="col-sm-1">
	   <i class="fa fa-eur"></i> 
	 </div><div class="col-sm-11">';

	$sum = $lisaHinta+$model->hinta;
	$body .= '<span id="hinta">'.number_format($sum, 2, ',', '').'</span> &euro;';
	$_SESSION['onlinevaraus']['amount'] = $sum;

	$body .= '
	 </div>
	</div>';


	$body .= $blockAika;
	$body .= $blockKohde;

	$body .= '
	   </div>
	</div>';

	if(isset($sivu) and $sivu == 'index')
	$body .= CHtml::link('Valitse aika','aika', array('class'=>'btn btn-success'));
	elseif(isset($sivu) and $sivu == 'aika')
	$body .= CHtml::link('Valitse osoite','osoite', array('class'=>'btn btn-success'));
	elseif(isset($sivu) and $sivu == 'osoite')
	$body .= CHtml::link('Maksan','maksu', array('class'=>'btn btn-success'));
	elseif(isset($sivu) and $sivu == 'maksu')
	$body .= CHtml::link('Kassalle','kassalle', array('class'=>'btn btn-success'));

	$body .= CHtml::link('Keskeytä','index?keskeyta=true', array('class'=>'btn btn-warning'));

	echo json_encode($body);

  } 
?>
