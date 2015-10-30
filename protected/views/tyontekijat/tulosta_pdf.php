<?php

?>
<style>
td{ padding: 3px 7px; }
</style>

<h1><?php echo CHtml::encode($model->tekijan_nimi); ?></h1>

<table>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_katuosoite')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_katuosoite); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_pnumero')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_pnumero); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_ptoimipaikka')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_ptoimipaikka); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_email')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_email); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_henkilotunnus')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_henkilotunnus); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_puh')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_puh); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tyoryhma')); ?>:</td><td>
	<?php echo CHtml::encode($model->tyoryhma); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tyoehtosopimus')); ?>:</td><td>
	<?php echo CHtml::encode($model->tyoehtosopimus); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_pankkitili')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_pankkitili); ?>
	</td></tr>
	 
	<tr><td><?php echo CHtml::encode($model->getAttributeLabel('tekijan_konttori')); ?>:</td><td>
	<?php echo CHtml::encode($model->tekijan_konttori); ?>
	</td></tr>

</table>

<?php
		$ts = Tyosuhdet::model()->find(" tid='".$model->id."' ");
		if(isset($ts['id'])) :
?>
<hr>

<h1>TYÖSUHTEET</h1>

<table>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('alku')); ?>:</td><td>
	<?php echo CHtml::encode($ts->alku); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('loppu')); ?>:</td><td>
	<?php echo CHtml::encode($ts->loppu); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('vktyoaika')); ?>:</td><td>
	<?php echo CHtml::encode($ts->vktyoaika); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('nimike')); ?>:</td><td>
	<?php echo CHtml::encode($ts->nimike); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('palkkausmuoto')); ?>:</td><td>
	<?php echo CHtml::encode($ts->palkkausmuoto); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('tuntihinta')); ?>:</td><td>
	<?php echo CHtml::encode($ts->tuntihinta); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('matka_thinta')); ?>:</td><td>
	<?php echo CHtml::encode($ts->matka_thinta); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('lippu_kuumaks')); ?>:</td><td>
	<?php echo CHtml::encode($ts->lippu_kuumaks); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('koe_loppu')); ?>:</td><td>
	<?php echo CHtml::encode($ts->koe_loppu); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('koe_hinta')); ?>:</td><td>
	<?php echo CHtml::encode($ts->koe_hinta); ?>
	</td></tr>


</table>
<hr>

<h1>VEROPROSENTTI</h1>

<table>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('tuloraja_ajalle')); ?>:</td><td>
	<?php echo CHtml::encode($ts->tuloraja_ajalle); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('perusprosentti')); ?>:</td><td>
	<?php echo CHtml::encode($ts->perusprosentti); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('lisaprosentti')); ?>:</td><td>
	<?php echo CHtml::encode($ts->lisaprosentti); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('kuukaudessa')); ?>:</td><td>
	<?php echo CHtml::encode($ts->kuukaudessa); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('kahdessa_viikossa')); ?>:</td><td>
	<?php echo CHtml::encode($ts->kahdessa_viikossa); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('viikossa')); ?>:</td><td>
	<?php echo CHtml::encode($ts->viikossa); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('paivassa')); ?>:</td><td>
	<?php echo CHtml::encode($ts->paivassa); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('atk_varten')); ?>:</td><td>
	<?php echo CHtml::encode($ts->atk_varten); ?>
	</td></tr>

	<tr><td><?php echo CHtml::encode($ts->getAttributeLabel('yksi_tuloraja')); ?>:</td><td>
	<?php echo CHtml::encode($ts->yksi_tuloraja); ?>
	</td></tr>



</table>
<?php endif; ?>
