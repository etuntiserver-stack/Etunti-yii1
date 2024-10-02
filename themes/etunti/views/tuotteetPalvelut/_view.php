<?php
/* @var $this KohteetController */
/* @var $data Kohteet */
/*
if( $data->alvsis == 'nolla'){
	$result = round((($data->hinta_alv_0 * $data->alv) / 100) + $data->hinta_alv_0, 6);
}
if( $data->alvsis == 'sis'){
    $clr_alv = str_replace(".", "", $data->alv);
	$result = round($data->hinta_alv_sis / floatval('1.' . $clr_alv), 6);
}
*/
?>

<tr>
	<td>
		<?php echo CHtml::link('<i class="fa fa-pencil-square-o" aria-hidden="true" style="font-size: 110%"></i>', 
				array('update', 'id'=>$data->id), 
				array(
					'class'=>'btn btn-primary myBgColors', 
					'style'=>'color:white', 
					'data-toggle'=>'tooltip', 
					'data-placement'=>'top', 
					'title'=>Yii::t('main', 'Muokkaa') 
				)
			); 
		?>
	</td>
	<td class="text-center">
		<?php if($data->hinta_alv_0 !=0 and $data->yksikko == 'h' and $data->nayta_vain_onlinevarauksessa == 0): ?>
		<input type="radio" name="oletustuote" id="oletustuote" value="<?=$data->id?>" <?=(($data->oletustuote == 2)?'checked':'')?>>
		<?php endif; ?>
	</td>
	<td class="text-center">
		<?=(isset($getAll['tv'][$data->id]))?$getAll['tv'][$data->id]:''?>
		<?=(isset($getAll['lisa_tuotteet'][$data->id]))?$getAll['lisa_tuotteet'][$data->id]:''?>
	</td>
	<td>
		<h3><?php echo $data->nimike; ?></h3>
	</td>
	<td>
		<?php if(is_array(json_decode($data->kategoria, true))) : ?>
		<?php $kat = json_decode($data->kategoria, true); ?>
		<?php foreach($kat as $itm): ?>
		 <div class="row"><div class="col-sm-12"><?=$itm?></div></div>
		<?php endforeach; ?>
		<?php endif; ?>
	</td>
	<td>
		<?php echo $data->hinta_alv_0; ?>
	</td>
	<td>
		<?php echo $data->alv; ?>
	</td>
	<td>
		<?php echo $data->hinta_alv_sis; ?>
	</td>
	<?php /* if($netvisor) : ?>
	<td>
		<?=$data->netvisorkey?>
	</td>
	<?php endif; */ ?>
</tr>

