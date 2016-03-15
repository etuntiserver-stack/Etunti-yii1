<?php

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

	$body .= '
	 </div>
	</div>


	   </div>
	</div>


	'.CHtml::link('Valitse aika','aika', array('class'=>'btn btn-success')).'

	';
	echo json_encode($body);

  } 
?>
