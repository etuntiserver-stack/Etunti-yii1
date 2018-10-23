<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

	if($raporti_tyyppi == 'Luetut')
	$m = $this->tidFromTo_luetut($data->id, $from, $to, $osoite);
	if($raporti_tyyppi == 'Toteutuneet')
	$m = $this->tidFromTo_toteutuneet($data->id, $from, $to, $osoite);
?>

<?php if(isset($m) 
	and ($raporti_tyyppi == 'Luetut' or $raporti_tyyppi == 'Toteutuneet')
	): ?>
<?php foreach($m as $k=>$val): ?>
<?php
	$viesti = '';
	if($val->viesti != '' and $val->viesti != 'xxx')
	$viesti = $val->viesti;
?>
<tr>
	<td><?=$this->etuSukunimi($data->id)?></td>
	<td><?=$val->kohde_kannasta;?></td>
	<td><?=date("d.m.Y", strtotime($val->aloitan))?></td>
	<td data-order="<?=strtotime($val->aloitan)?>"><?=date("H:i", strtotime($val->aloitan))?></td>
	<td data-order="<?=strtotime($val->loppui)?>"><?=date("H:i", strtotime($val->loppui))?></td>
	<td><?=$this->sprint($val->l_tunnit);?>&nbsp;|&nbsp;<?=$this->num($val->l_tunnit);?></td>
	<td><?=$viesti?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

