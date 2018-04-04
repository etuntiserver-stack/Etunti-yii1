<?php

?>

<!--Kohteet-->
<h3>Kohteet</h3>
<table class="table table-bordered">
<tr>
	<th><?=Yii::t('main', 'Osoite')?></th>
	<th><?=Yii::t('main', 'Sähköposti')?></th>
	<th><?=Yii::t('main', 'Puhelin')?></th>
</tr>
<?php foreach($kohteet as $k) : ?>
<tr class="text-danger">
	<td><?=$k->osoite?></td>
	<td><?=$k->email?></td>
	<td><?=$k->puh_nro?></td>
</tr>
<?php endforeach; ?>
</table>
<!--/Kohteet-->
<br>
<!--Kohteista-->
<?php 
   $sum_mobiili = 0;
   $sum_avaimet = 0;
   $sum_onlinevaraus = 0;
   $sum_laskut 	= 0;
   $sum_lh	= 0;
   $laskut = Lasku::model()->findAll(" as_nro='".$asiakas->asiakasnumero."' ");
   $sum_laskut = count($laskut);
   foreach($laskut as $l){
   	$lh = LaskuHistoria::model()->findAll(" lid='".$l->id."' ");
	$sum_lh += count($lh);
   }

   foreach($kohteet as $k){
	$mob = Mobile::model()->findAll(" kohdenID='".$k->id."' ");
	$tot = Toteutuneet::model()->findAll(" kohdenID='".$k->id."' ");
	$sum_mobiili += count($mob)+count($tot);

	$avaimet = Avaimet::model()->findAll(" kohde='".$k->id."' ");
	$sum_avaimet += count($avaimet);
	$ov = Onlinevaraus::model()->findAll(" kohde_id='".$k->id."' ");
	$sum_onlinevaraus += count($ov);
   }
?>
<h3 class="text-danger">Mobiili taulusta <?=$sum_mobiili?> riveja.</h3>
<h3 class="text-danger">Avaimet taulusta <?=$sum_avaimet?> riveja.</h3>
<h3 class="text-danger">Onlinevaraus taulusta <?=$sum_onlinevaraus?> riveja.</h3>
<h3 class="text-danger">Laskut taulusta <?=$sum_laskut?> riveja.</h3>
<h3 class="text-danger">Laskut historia taulusta <?=$sum_lh?> riveja.</h3>
<!--/Kohteista-->
