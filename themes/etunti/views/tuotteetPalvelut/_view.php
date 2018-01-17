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
		<h3><?php echo $data->nimike; ?></h3>
	</td>
	<td>
		<?php $kat = json_decode($data->kategoria, true); ?>
		<?php foreach($kat as $itm): ?>
		 <div class="row"><div class="col-sm-12"><?=$itm?></div></div>
		<?php endforeach; ?>
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
<?php /*
	<td>
		<?php echo $alasvetovaliko; ?>
	</td>
	<td>
		<?php echo $lisapalvelut; ?>
	</td>
*/ ?>
</tr>

