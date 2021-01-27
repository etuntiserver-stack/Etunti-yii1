<?php
/* @var $this ViestintaController */
/* @var $data Viestinta */

	$data = $arr['data'];

	$asiakas = '';
	$call = '';
	$get_kohde = Kohteet::model()->findByPk($data->kohde);
	if(isset($get_kohde->asiakkaat))
	{
		if( $get_kohde->asiakkaat->tyyppi == 'yritys' ){
			$asiakas = $get_kohde->asiakkaat->yrityksen_nimi;
		} elseif( $get_kohde->asiakkaat->tyyppi == 'henkilo' ){
			$asiakas = $get_kohde->asiakkaat->yhteyshenkilo;
		}
		if( $get_kohde->asiakkaat->puhelin != '' ){
			$call = '&nbsp;&nbsp;<a href="tel:'.$get_kohde->asiakkaat->puhelin.'"><i class="fa fa-2x fa-phone"></i></a>';
		}
	}
?>

<tr>
	<td>
	<?=
		CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>',
		[
		  sprintf('/tyovuoroot/beta?mode=vko&year=%s&week=%s&tv_id=%s', date("Y", strtotime($arr['this_pvm'])), date("W", strtotime($arr['this_pvm'])), $arr['this_id'])
		],
		[
		  'class' => 'btn btn-primary myBgColors',
		  'style' => 'color:white',
		  'data-toggle' => 'tooltip',
		  'data-placement' => 'top',
		  'title' => Yii::t('main', 'Muokkaa'),
		  'target' => '_blank'
		]
		)
	?>
	</td>
	<td>
		<?php if($data->tid == 0): ?>
		<?=Yii::t('main', 'VARAUS')?>
		<?php else: ?>
		<?=$this->etuSukunimi($arr['this_tid'])?>
		<?php endif; ?>
	</td>
	<td><?=$arr['this_pvm']?></td>
	<td><?=$data->alku?>-<?=$data->loppu?></td>
	<td><?=$asiakas?> <?=$call?></td>
	<td><?=$data->osoite?></td>
	<td><?=($data->status != 0)?$this->tilanteet()[$data->status]:''?></td>
</tr>


