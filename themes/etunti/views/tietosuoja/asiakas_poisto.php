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
<tr>
	<td class="text-danger"><?=$k->osoite?></td>
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
   $sum_tv	= 0;
   $sum_onlinevaraus = 0;
   $sum_laskut 	= 0;
   $sum_lh	= 0;
   $sum_toistuvatv = 0;
   $sum_tk	= 0;
   $sum_tk_rivi = 0;

   $laskut = Lasku::model()->findAll(" as_nro='".$asiakas->asiakasnumero."' ");
   $sum_laskut = count($laskut);
   $palautteet = Palautteet::model()->findAll(" asiakas_id='".$asiakas->id."' ");
   $sum_palautteet = count($palautteet);
   $vinkit = VinkkiExtranet::model()->findAll(" asiakas_id='".$asiakas->id."' ");
   $sum_vinkit = count($vinkit);
   $tarjoukset = CrmTarjoukset::model()->findAll(" asiakas_id='".$asiakas->id."' ");
   $sum_tarjoukset = count($tarjoukset);
   $sopimukset = CrmSopimukset::model()->findAll(" asiakas_id='".$asiakas->id."' ");
   $sum_sopimukset = count($sopimukset);
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
	$tv = Tyovuoroot::model()->findAll(" kohde='".$k->id."' ");
	$sum_tv += count($tv);
	$toistuvatv = ToistuvatTyovuorot::model()->findAll(" kohde='".$k->id."' ");
	$sum_toistuvatv += count($toistuvatv);
	$tk = Tyonkuvaus::model()->findAll(" kohde_id='".$k->id."' ");
	foreach($tk as $rivi){
	  $tkrivi = TyonkuvausRivit::model()->findAll(" tyonkuvaus_id='".$rivi->id."' ");
	  $sum_tk_rivi += count($tkrivi);
	}
	$sum_tk += count($tk);
   }
?>
<table class="table table-bordered">
<tr><td>Työvuorot</td><td class="text-danger"><?=$sum_tv?> riveja.</td></tr>
<tr><td>Toistuva työvuorot</td><td class="text-danger"><?=$sum_toistuvatv?> riveja.</td></tr>
<tr><td>Mobiili</td><td class="text-danger"><?=$sum_mobiili?> riveja.</td></tr>
<tr><td>Avaimet</td><td class="text-danger"><?=$sum_avaimet?> riveja.</td></tr>
<tr><td>Onlinevaraus</td><td class="text-danger"><?=$sum_onlinevaraus?> riveja.</td></tr>
<tr><td>Laskut</td><td class="text-danger"><?=$sum_laskut?> riveja.</td></tr>
<tr><td>Laskut historia</td><td class="text-danger"><?=$sum_lh?> riveja.</td></tr>
<tr><td>Palautteet</td><td class="text-danger"><?=$sum_palautteet?> riveja.</td></tr>
<tr><td>Vinkit</td><td class="text-danger"><?=$sum_vinkit?> riveja.</td></tr>
<tr><td>Työnkuvaukset</td><td class="text-danger"><?=$sum_tk?> riveja. (Sub riveja: <?=$sum_tk_rivi?>.)</td></tr>
<tr><td>Tarjoukset</td><td class="text-danger"><?=$sum_tarjoukset?> riveja.</td></tr>
<tr><td>Sopimukset</td><td class="text-danger"><?=$sum_sopimukset?> riveja.</td></tr>
</table>
<!--/Kohteista-->
