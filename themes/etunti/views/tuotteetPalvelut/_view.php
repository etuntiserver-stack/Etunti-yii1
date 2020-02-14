<?php
/* @var $this KohteetController */
/* @var $data Kohteet */

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
	<td>
		<?php if($data->hinta_alv_0 !=0 and $data->yksikko == 'h' and $data->nayta_vain_onlinevarauksessa == 0): ?>
		<br><input type="radio" name="oletustuote" id="oletustuote" value="<?=$data->id?>" <?=(($data->oletustuote == 2)?'checked':'')?>>
		<?php endif; ?>
	</td>
	<td>
		<?=(isset($getAll['tv'][$data->id]))?'TV:'.$getAll['tv'][$data->id].'kpl':''?>
		<?php /* (isset($getAll['lisa_tuotteet'][$data->id]))?'<br>Lisätuotteet:'.$getAll['lisa_tuotteet'][$data->id].'kpl':'' */?>
		<?=(isset($getAll['ketjussa'][$data->id]))?'<br>Ketjussa:'.$getAll['ketjussa'][$data->id].'kpl':''?>
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

