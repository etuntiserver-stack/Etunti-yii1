<?php

  if(isset($_SESSION['onlinevaraus']['paapalvelu']))
	$model = TuotteetPalvelut::model()->findbypk($_SESSION['onlinevaraus']['paapalvelu']);


  // <-- Kupongi
  $blockKupongi = '';
  if(isset(Yii::app()->user->alennuskoodi) and !empty($this->Kupongi_checker(Yii::app()->user->alennuskoodi)))
  {
	$_SESSION['onlinevaraus']['kupongi'] = $this->Kupongi_checker(Yii::app()->user->alennuskoodi);
  }

  if(isset($_SESSION['onlinevaraus']['kupongi']))
  {
	$kup = Kupongit::model()->findbypk($_SESSION['onlinevaraus']['kupongi']);

	if(isset($kup->id))
	{

		if($kup->maara_tyyppi == 'euro')
		$kup_maara = '-'.$kup->euro_maara.' &euro;';
		if($kup->maara_tyyppi == 'prosentti')
		$kup_maara = '-'.$kup->prosentti_maara.'%';

		$blockKupongi .= '
		<div class="row">
			<div class="col-xs-2">
				<i class="fa fa-star fa-2x" aria-hidden="true"></i>
				</div><div class="col-xs-10">
				'.Yii::t('main', 'Alennuskoodi').': '.$kup_maara.'
			</div>
		</div>
		';
	}
  }
  //     Kupongi -->

  $blockAika = '';
  $vkolisa = 0;

  if(isset($_SESSION['onlinevaraus']['modelTV']))
  {
	$tv = Tyovuoroot::model()->findbypk($_SESSION['onlinevaraus']['modelTV']);
	if(isset($tv->id))
	{

	   $pyhat = $this->pyhatCheck($tv->pvm);
	   if($pyhat == 'pyhat')
	   $vkolisa = 2;
	   elseif($pyhat == 'lauantai')
	   $vkolisa = 1.5;
	   
	   $filepath = dirname(Yii::app()->getBasePath())."/img/tekijat/".$_SESSION['domain']."/".$tv->tid.".jpg";
	   if (file_exists($filepath)){
		$imageData = base64_encode(file_get_contents($filepath));
		$src = 'data: '.mime_content_type($filepath).';base64,'.$imageData;
		$kuva = '<img src="'.$src.'" class="img-thumbnail">';
	   } else {
	   	$kuva = '<img src="../../lib/img/noname.jpg" class="img-thumbnail">';
	   }


	$blockAika .= '
	<hr>
     	<h3 id="varattu_aika" for="'.strtotime($tv->alku).'" pvm="'.$tv->pvm.'" klo="'.$tv->alku.'-'.$tv->loppu.'">Varattu aika</h3>
	<div class="row">
	 <div class="col-xs-4">
		'.$kuva.'		
		'.$this->etuSukunimi($tv->tid).'
	 </div><div class="col-xs-8">
		<span id="varattu_osoite"></span>
		'.$tv->pvm.'<br>
		Klo.: '.$tv->alku.'-'.$tv->loppu.'
	 </div>
	</div>
 	';
	}
  }

  $blockKohde = '';
  if(isset($_SESSION['onlinevaraus']['modelKohde']))
  {
	$k = Kohteet::model()->findbypk($_SESSION['onlinevaraus']['modelKohde']);
	if(isset($k->id))
	{
	$blockKohde = '
	<hr>
     	<label>Osoite </label><br>
	<div class="row">
	 <div class="col-xs-4">
		Asiakas: '.$k->asiakas_id.'		
	 </div><div class="col-xs-8">
		'.$k->osoite.'
	 </div>
	</div>
 	';
	}
  }




  if(isset($model->id))
  {
	$tilauksenKuvaus = array();
	$perusAlv	= 0;
	$perusHinta	= 0;
	$perusKesto	= 0;
	$lisaHinta 	= 0;
	$lisaTunti 	= 0;
	$lisapalvelut 	= '';
	$nimike 	= '';
	$otsikko 	= '';
	$tyo_toimialue	= '';
	$kotitalousvahennys = '';

	if(!empty($model->alv) and $model->alv != 0)		$perusAlv	= $model->alv;
	$_SESSION['onlinevaraus']['alv'] 					= $perusAlv;

	if(!empty($model->hinta) and $model->hinta != 0)	$perusHinta	= $model->hinta;
	if(!empty($model->kesto) and $model->kesto != 0)	$perusKesto	= $model->kesto;
	if(isset($_SESSION['onlinevaraus']['paa_otsikko'])) 	$otsikko 	= $_SESSION['onlinevaraus']['paa_otsikko']; 
	if(isset($_SESSION['onlinevaraus']['paa_nimike'])) 	$nimike 	= ': '.$_SESSION['onlinevaraus']['paa_nimike']; 
	if(isset($_SESSION['onlinevaraus']['tyo_toimialue'])) 	$tyo_toimialue 	= $_SESSION['onlinevaraus']['tyo_toimialue'];
	$tilauksenKuvaus['paa'][$model->nimike] 		= $otsikko.$nimike;
	$tilauksenKuvaus['alv']			 		= $perusAlv;

	if(isset($_SESSION['onlinevaraus']['lisapalvelut']) and !empty($_SESSION['onlinevaraus']['lisapalvelut']))
	{
		foreach($_SESSION['onlinevaraus']['lisapalvelut'] as $p)
		{
		    	if(isset($p[0]) and isset($p[1]) and isset($p[2]))
		    	{
				$lisaHinta += $p[1];
				$lisaTunti += $p[2];
				$lisapalvelut .= '
				'.$p[0].' <span style="opacity:0.6">'.$p[2].'</span>
				<span style="opacity:0.6">h</span></span><br>
				';
				$tilauksenKuvaus['lisa'][$p[0]] = $p[2];
		    	}
		}
	}



	if(isset($_SESSION['onlinevaraus']['paa_hinta'])) $paa_hinta = $_SESSION['onlinevaraus']['paa_hinta']; else $paa_hinta = 0;
	if(isset($_SESSION['onlinevaraus']['paa_kesto'])) $paa_kesto = $_SESSION['onlinevaraus']['paa_kesto']; else $paa_kesto = 0;

	$sumTunti = $lisaTunti+$paa_kesto; //$perusKesto+

	$_SESSION['onlinevaraus']['sumTunti'] 	= $sumTunti;
	$tilauksenKuvaus['sumTunti'] 		= $sumTunti;


	$paa_hinta = $perusHinta+$paa_hinta;
	if($vkolisa > 0)
		$sum = ((float)$lisaHinta+$paa_hinta)*$vkolisa;
	else
		$sum = (float)$lisaHinta+$paa_hinta;

	// <-- kupongi
	if(isset($kup->id) and $kup->maara_tyyppi == 'euro' and $sum > $kup->euro_maara)
	{
		$sum -= $kup->euro_maara;
	}
	if(isset($kup->id) and $kup->maara_tyyppi == 'prosentti' and $sum > 0)
	{
		$sum -= ($sum*$kup->prosentti_maara)/100;
	}
	//     kupongi -->

	$_SESSION['onlinevaraus']['amount'] 	= $sum;
	$tilauksenKuvaus['sum'] 		= $sum;


	if(isset($model->kotitalousvahennys) and !empty($model->kotitalousvahennys))
	{
		$s = $sum-(($sum*(int)$model->kotitalousvahennys)/100);
		$kotitalousvahennys = '<br><span>Kotitalousvähennys: '.number_format($s, 2, ',', '').'</span> &euro;';
		$tilauksenKuvaus['KotitalousVahennys'] = number_format($s, 2, ',', '');
	}


	$_SESSION['onlinevaraus']['tilauksenKuvaus'] = $tilauksenKuvaus;



	$body = 
	'
<div class="row select-service-panel bottom">
   <div class="panel-heading">
      <h3>
       <button class="pull-right btn btn-warning" data-toggle="collapse" data-target="#order_summary" id="show_yhteenveto">'.Yii::t('main', 'Näytä lisää').'</button>
	'.Yii::t('main', 'Yhteenveto').'
      </h3>
   </div>
   <div class = "panel-body collapse" id="order_summary">
	';

	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-home fa-2x"></i> 
	 </div><div class="col-xs-10">
		<span>'.$model->nimike.'</span>
	 </div>
	</div>';

	if( !empty($otsikko) and !empty($nimike) )
	{
	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   <i class="fa fa-plus fa-2x"></i> 
	 </div><div class="col-xs-10">
		<span>'.$otsikko.$nimike.'</span>
	 </div>
	</div>';
	}

	if(!empty($lisapalvelut))
	{
	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-plus fa-2x"></i> 
	 </div><div class="col-xs-10">
		'.$lisapalvelut.'
	 </div>
	</div>';
	}

	if(!empty($tyo_toimialue))
	{
	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-map-marker fa-2x"></i> 
	 </div><div class="col-xs-10">
		'.$tyo_toimialue.'
	 </div>
	</div>';
	}

	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-clock-o fa-2x"></i> 
	 </div><div class="col-xs-10">
		<span id="clock" val="'.$sumTunti.'">'.number_format($sumTunti, 1, ',', '').'</span> tuntia
	 </div>
	</div>';

	$body .= $blockKupongi;

	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-eur fa-2x"></i> 
	 </div><div class="col-xs-10">
		<span id="hinta">'.number_format($sum, 2, ',', '').'</span> &euro;
		'.$kotitalousvahennys.'
	 </div>
	</div>';

	if(!isset($kup->id) and isset($sivu) and $sivu == 'index')
	{
	$body .= '
	<div class="row">
	 <div class="col-xs-2">
	   	<i class="fa fa-gift fa-2x"></i> 
	 </div>
	 <div class="col-xs-10">
	    <div class="input-group">
	      <input type="text" class="form-control kupongi_id_p" placeholder="Alennuskoodi">
	      <span class="input-group-btn">
	        <button class="btn btn-warning kupongi_add_p" type="button">'.Yii::t('main', 'Käytä').'</button>
	      </span>
	    </div>
	    <div class="kupongi_result_p"></div>
	 </div>
	</div>';
	}

	$path = Yii::app()->basePath."/../tiedostot/onlinevaraus_tuote/".Yii::app()->user->domain;
	if(file_exists($path."/".$model->id.".jpg"))
	{
	$body .= '
	<div class="row">
	 <div class="col-xs-4">
		<br><p><img src="../../tiedostot/onlinevaraus_tuote/'.Yii::app()->user->domain.'/'.$model->id.'.jpg" class="img-thumbnail"></p>
	 </div>
	</div>';
	}

	$body .= $blockAika;
	$body .= $blockKohde;

	// <-- Tiedostot
	$body .= '<br>';
	$body .= CHtml::link(Yii::t('main','Onlinevaraus tietosuoja- ja rekisteriseloste'),
			array('/onlinevaraus/rekisteriseloste')
		);

	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/onlinevarausehdot.*')) as $file) 
	{
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		$body .= '<br>'.CHtml::link(Yii::t('main', 'Onlinevarausehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	}

	foreach(array_reverse(glob(Yii::app()->baseUrl.'tiedostot/firma/'.Yii::app()->user->domain.'/Konevuokraus_toimitusehdot.*')) as $file) 
	{
		$explNimi = explode("/",$file);
		// <-- file_safe_opener
		$filepath = 'tiedostot/firma/'.Yii::app()->user->domain.'/'.end($explNimi);
		$body .= '<br>'.CHtml::link(Yii::t('main', 'Konevuokraus toimitusehdot'),
			array('/site/file_safe_opener', 'filepath' => $filepath, 'ext' => 'pdf'),
			array('target'=>'_blank','class'=>'text-danger'
		));
		//     file_safe_opener -->
	
	}
	// <-- Tiedostot -->

	$body .= '
   </div>
</div>'; //panel

	echo json_encode($body);

  } 
?>
