<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */
?>
<?php foreach($m as $k=>$val): ?>
<?php
	$viesti = '';
	if($val->viesti != '' and $val->viesti != 'xxx')
	$viesti = $val->viesti;
?>
<tr>
	<td><?=$this->etuSukunimi($data->id)?></td>
	<td><?=$this->statusMuutosNimeksi($val->status)?></td>
	<td><?=((isset($val->kohteet->osoite))?$val->kohteet->osoite:'');?></td>
	<td><?=date("d.m.Y", strtotime($val->aloitan))?></td>
	<td data-order="<?=strtotime($val->aloitan)?>"><?=date("H:i", strtotime($val->aloitan))?></td>
	<td data-order="<?=strtotime($val->loppui)?>"><?=date("H:i", strtotime($val->loppui))?></td>
	<td><?php if($val->l_tunnit > 0): ?><?=$this->sprint($val->l_tunnit);?>&nbsp;|&nbsp;<?=$this->num($val->l_tunnit);?><?php endif; ?></td>
</tr>
<?php endforeach; ?>

