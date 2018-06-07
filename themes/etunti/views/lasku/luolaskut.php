<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU AUTOMAATTIO ESIKATSELLU'); ?></h2>

	</div>

<style>
.laatikko{
	height: 200px;
	max-height: 200px;
	overflow-y: scroll; 
	-ms-overflow-style: none;  // IE 10+
	overflow: -moz-scrollbars-none;  // Firefox
}
.laatikko::-webkit-scrollbar { 
    display: none;  // Safari and Chrome
}
.well{
	background: none;
}
.container-fluid{
	margin-top: 30px;
}
</style>


<p>
<div class="container-fluid">
	<div class="row">
	<?php foreach($lista as $item) : ?>
	<?php if( 
		isset($item->tyovuoroot->id) 
		and isset($item->kohteet->id) 
		and is_array(json_decode($item->tyovuoroot->tuoteID, true)) ) 
	: ?>
	<?php $asiakas = Asiakkaat::model()->findByPk($item->kohteet->asiakas_id); ?>
	<?php if( isset($asiakas->id) ) :  ?>
		<div class="col-sm-4">
		 <div class="panel panel-default laatikko">
		  <!-- Default panel contents -->
		  <div class="panel-heading"><?=$this->asiakasmuutos($asiakas)?></div>
		  <div class="panel-body">
		    <p><?=Yii::t('main', 'Työvuoro')?>: <?=$item->tyovuoroot->pvm?>, Klo.: <?=$item->tyovuoroot->alku?>-<?=$item->tyovuoroot->loppu?></p>
		  </div>

		  <!-- List group -->
		  <ul class="list-group">
		    <li class="list-group-item"><?=Yii::t('main', 'Viivästyskorko')?>: 
			<b class="pull-right"><?=((!empty($asiakas->viivastyskorko))? $asiakas->viivastyskorko: '<span class="text-danger">Ei tietoja</span>') ?></b></li>
		    <li class="list-group-item"><?=Yii::t('main', 'Maksuehto')?>: 
			<b class="pull-right"><?=((!empty($asiakas->maksuehto))? $asiakas->maksuehto: '<span class="text-danger">Ei tietoja</span>') ?></b></li>
		  </ul>
		 </div>
		</div>
   	<?php endif; ?>
   	<?php endif; ?>
	<?php endforeach; ?>
	</div>
	<center><button class="btn btn-lg btn-primary myBgColors"><?=Yii::t('main', 'LUO LASKUT')?></button></center>
</div>
</p>
