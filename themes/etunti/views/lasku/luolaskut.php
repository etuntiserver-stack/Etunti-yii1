<?php

?>
        <!-- begin: .tray-center -->
        <div class="tray-center">

        <h2 class="myBgColors p10"> <i class="fa fa-barcode"></i> <?php echo Yii::t('main', 'LASKU AUTOMAATTIO ESIKATSELLU'); ?></h2>

	</div>

<style>
.laatikko{
	height: 375px;
	max-height: 375px;
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
	<?php $is_ok = true; ?>
		<div class="col-sm-4">
		 <div class="panel panel-default laatikko">
		  <!-- Default panel contents -->
		  <div class="panel-heading"><?=$this->asiakasmuutos($asiakas)?></div>
		  <div class="panel-body">
		    <p><center><h4>
			<?=Yii::t('main', 'Työvuoro')?>: <?=$item->tyovuoroot->pvm?>, Klo.: <?=$item->tyovuoroot->alku?>-<?=$item->tyovuoroot->loppu?>
		    </h4></center></p>
		  </div>

		  <!-- List group -->
		  <ul class="list-group">
		    <li class="list-group-item"><?=Yii::t('main', 'Laskutus kanava')?>: 
			<b class="pull-right">
			<?php
				if(!empty($asiakas->laskutus_kanava)){ 
					echo Yii::t('main', $asiakas->laskutus_kanava);
				} else {
					echo '<i class="text-danger fa fa-ban"></i>';
					$is_ok = false;
				}
			?>
			</b>
		    </li>
		    <li class="list-group-item"><?=Yii::t('main', 'Eräpäivä')?>: 
			<b class="pull-right">
			<?php
				if(!empty($asiakas->maksuehto)){ 
					echo date("d.m.Y",strtotime("+$asiakas->maksuehto day"));
				} else {
					echo '<i class="text-danger fa fa-ban"></i>';
					$is_ok = false;
				}
			?>
			</b>
		    </li>
		    <li class="list-group-item"><?=Yii::t('main', 'Viivästyskorko')?>: 
			<b class="pull-right">
			<?php
				if(!empty($asiakas->viivastyskorko)){ 
					echo $asiakas->viivastyskorko;
				} else {
					echo '<i class="text-danger fa fa-ban"></i>';
					$is_ok = false;
				}
			?>
			</b>
		    </li>
		    <li class="list-group-item"><?=Yii::t('main', 'Maksuehto')?>: 
			<b class="pull-right">
			<?php
				if(!empty($asiakas->maksuehto)){ 
					echo $asiakas->maksuehto;
				} else {
					echo '<i class="text-danger fa fa-ban"></i>';
					$is_ok = false;
				}
			?>
			</b>
		    </li>
		    <li class="list-group-item">
			<center><h4 class="link" data-toggle="collapse" data-target="#open_rivit_<?=$item->id?>">
				<?=Yii::t('main', 'Lasku rivit')?> <i class="caret"></i>
		  	</h4></center>
		    </li>
		    <li id="open_rivit_<?=$item->id?>" class="collapse well"><br>
		    <?php if( is_array(json_decode($item->tyovuoroot->tuoteID, true)) ) : ?>
		     <table class="table">
		     <tr>
			<th>Tuote</th>
			<th>Hinta</th>
			<th>Yksikkö</th>
			<th>Määrä</th>
			<th>Yhteensä</th>
		     </tr>
		     <?php
			$mob 	= $this->num(strtotime($item->loppui)-strtotime($item->aloitan));
			$h 	= 35;
			$y 	= 'h';
			$yht	= ($h*$mob);
		     ?>
		     <tr>
			<td><?=Yii::t('main', 'Tunnit')?></td>
			<td><?=$h?></td>
			<td><?=$y?></td>
			<td><?=$mob?></td>
			<td><?=number_format($yht, 2, ',', ' ')?></td>
		     </tr>
		     <?php foreach(json_decode($item->tyovuoroot->tuoteID, true) as $tuote_id) : ?>
		     <?php
			$t 	= 1;
			$rivi_kpl 	= 0;
			$r		= [];
			$r 		= $this->hinnastoHintaat($tuote_id, $asiakas->asiakasnumero, $item->kohteet, $t, $rivi_kpl);
			$kpl 		= $r['kpl'];
			$hinta 		= $r['hinta'];
			$alv 		= $r['alv'];
			$yksikko	= $r['yksikko'];
			$yht		= ($r['kpl']*$r['hinta']);
		     ?>
		     <tr>
			<td><?=$r['tp_nimike']?></td>
			<td><?=$r['hinta']?></td>
			<td><?=$r['yksikko']?></td>
			<td><?=$r['kpl']?></td>
			<td><?=number_format($yht, 2, ',', ' ')?></td>
		     </tr>
		     <?php endforeach; ?>
		     </table>
		    <?php endif; ?>
		    </li>

		    <?php if(!$is_ok) : ?>
		    <li class="list-group-item text-danger"><center><h4><?=Yii::t('main', 'Puuttuu tiedot')?></h4></center></li>
		    <?php else : ?>
		    <li class="list-group-item text-success"><center><h4><?=Yii::t('main', 'Lasku ok')?></h4></center></li>
		    <?php endif; ?>
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
