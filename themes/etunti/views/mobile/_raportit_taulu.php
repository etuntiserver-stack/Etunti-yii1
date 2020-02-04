<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */
?>
<?php foreach($m as $val): ?>
<?php
	$tyontekijan_nimi = '';
	if( $mob_or_tv == 'mob' ){
		$pvm = date("d.m.Y", strtotime($val->aloitan));
		$aloitus = $val->aloitan;
		$lopetus = $val->loppui;
		$tyontekijan_nimi = $this->etuSukunimi($val->id);
	}
	if( $mob_or_tv == 'tv' and isset($val['data']) ){
		$data = $val['data'];
		$val = $data;
		$val->l_tunnit = strtotime($val->loppu)-strtotime($val->alku);
		$pvm = $val->pvm;
		$aloitus = $val->alku;
		$lopetus = $val->loppu;
		$tyontekijan_nimi = $this->etuSukunimi($data->tid);
	}
?>
<tr>
	<td><?=$tyontekijan_nimi?></td>
	<td><?=$this->statusMuutosNimeksi($val->status)?></td>
	<td><?=((isset($val->kohteet->osoite))?$val->kohteet->osoite:'');?></td>
	<td><?=$pvm?></td>
	<td data-order="<?=strtotime($aloitus)?>"><?=date("H:i", strtotime($aloitus))?></td>
	<td data-order="<?=strtotime($lopetus)?>"><?=date("H:i", strtotime($lopetus))?></td>
	<td><?php if($val->l_tunnit > 0): ?><?=$this->sprint($val->l_tunnit);?>&nbsp;|&nbsp;<?=$this->num($val->l_tunnit);?><?php endif; ?></td>
</tr>
<?php endforeach; ?>

